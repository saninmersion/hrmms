<?php

namespace App\Infrastructure\Support\Unicode;

use Illuminate\Support\Arr;

/**
 * Class Number
 * @package App\Infrastructure\Support\Unicode
 */
final class NepaliNumber
{
    /**
     * @var string
     */
    protected string $nepali;
    /**
     * @var string
     */
    protected string $english;
    /**
     * @var array
     */
    protected array $englishToNepaliDigits = [
        '0' => '०',
        '1' => '१',
        '2' => '२',
        '3' => '३',
        '4' => '४',
        '5' => '५',
        '6' => '६',
        '7' => '७',
        '8' => '८',
        '9' => '९',
    ];

    /**
     * Number constructor.
     *
     * @param string $locale
     *
     * @throws UnicodeNumberException
     */
    public function __construct(string $locale = 'en')
    {
        if ( !in_array($locale, ['en', 'ne']) ) {
            throw new UnicodeNumberException('Currently "ne" and "en" only supported as a `locale`.');
        }
    }

    /**
     * @param string|int|float $number
     *
     * @return string
     */
    public static function englishToNepali($number): string
    {
        return (new static())->setEnglish($number)->toNepali();
    }


    /**
     * @param string|int|float $number
     *
     * @return string
     */
    public static function nepaliToEnglish($number): string
    {
        return (new static())->setNepali($number)->toEnglish();
    }

    /**
     * @param string|int|float $nepali
     *
     * @return NepaliNumber
     */
    public function setNepali($nepali): self
    {
        $this->nepali = (string) $nepali;

        return $this;
    }

    /**
     * @param string|int|float $english
     *
     * @return NepaliNumber
     */
    public function setEnglish($english): self
    {
        $this->english = (string) $english;

        return $this;
    }

    /**
     * @return string
     */
    public function toNepali(): string
    {
        $stringNumber = preg_split('//', $this->english, -1, PREG_SPLIT_NO_EMPTY);

        return collect($stringNumber)->map(
            function ($number) {
                return Arr::get($this->englishToNepaliDigits, $number, $number);
            }
        )->implode('');
    }

    /**
     * @return string
     */
    public function toEnglish(): string
    {
        $stringNumber = preg_split('//u', $this->nepali, -1, PREG_SPLIT_NO_EMPTY);
        $numbersArray = array_flip($this->englishToNepaliDigits);

        return collect($stringNumber)->map(
            function ($number) use ($numbersArray) {
                return Arr::get($numbersArray, $number, $number);
            }
        )->implode('');
    }
}
