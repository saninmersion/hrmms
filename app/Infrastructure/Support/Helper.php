<?php

namespace App\Infrastructure\Support;

use App\Infrastructure\Constants\General;
use App\Infrastructure\Support\BikramSambat\BikramSambat;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Throwable;

/**
 * Class Helper
 * @package App\Infrastructure\Support
 */
class Helper
{
    /**
     * @param Blueprint $table
     * @param bool      $hasMetadata
     * @param bool      $hasSoftDeletes
     *
     * @return Blueprint
     */
    public static function commonMigration(Blueprint $table, bool $hasMetadata = true, bool $hasSoftDeletes = false): Blueprint
    {
        if ( $hasMetadata ) {
            $table->jsonb('metadata')->nullable();
        }

        $table->timestamps();

        if ( $hasSoftDeletes ) {
            $table->softDeletes();
        }

        return $table;
    }

    /**
     * @param Exception|Throwable $exception
     * @param string              $type
     */
    public static function logException($exception, string $type = 'error'): void
    {
        logger()->$type(
            $exception->getMessage(),
            [
                'message' => $exception->getMessage(),
                'code'    => $exception->getCode(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'trace'   => $exception->getTrace(),
            ]
        );
    }

    /**
     * @param array        $array
     * @param array|string $values
     *
     * @return array
     */
    public static function arrayExceptByValue(array $array, $values): array
    {
        if ( is_array($values) ) {
            foreach ($values as $value) {
                $array = self::arrayExceptByValue($array, $value);
            }

            return $array;
        }

        unset($array[array_search($values, $array)]);

        return array_values($array);
    }

    /**
     * @param Carbon|string|null $date
     *
     * @return array|null
     */
    public static function dateResponse($date): ?array
    {
        if ( !$date ) {
            return null;
        }

        $date = $date instanceof Carbon ? $date : Carbon::parse($date);

        return [
            'raw'       => $date->toDateTimeString(),
            'formatted' => $date->format(General::DATE_FORMAT),
            'diff'      => $date->diffForHumans(),
            'date'      => $date->format('Y-m-d'),
        ];
    }

    /**
     * @param string      $filePath
     * @param string|null $disk
     *
     * @return string
     */
    public static function fileUrl(string $filePath, ?string $disk = null): string
    {
        $disk = $disk ?: config('filesystems.default');

        return route('medias', [$disk, $filePath]);
    }

    /**
     * @param array  $provider
     * @param string $key
     */
    public static function withErrors(array $provider, $key = 'default'): void
    {
        $value  = new MessageBag((array) $provider);
        $errors = session()->get('errors', new ViewErrorBag());

        if ( !$errors instanceof ViewErrorBag ) {
            $errors = new ViewErrorBag();
        }

        session()->put('errors', $errors->put($key, $value));
    }

    /**
     * @param ViewErrorBag $errors
     *
     * @return array|null
     */
    public static function getErrors(ViewErrorBag $errors): ?array
    {
        $errors = $errors->getMessages();
        session()->remove('errors');

        return $errors ?: null;
    }

    /**
     * @param string|null $dateTime
     *
     * @return string
     */
    public static function parseDateTime(?string $dateTime): ?string
    {
        if ( $dateTime ) {
            $dateTime = \Illuminate\Support\Carbon::parse($dateTime)->format('Y-m-d g:ia');
        }

        return $dateTime;
    }

    /**
     * @return Carbon
     * @throws Exception
     */
    public static function deadline(): Carbon
    {
        $deadline = BikramSambat::bsToAd(config('config.deadline'));

        if ( !$deadline ) {
            throw new Exception('Invalid deadline.');
        }

        return $deadline;
    }

    /**
     * @param object|array $name
     *
     * @return string
     */
    public static function formatFullName($name): string
    {
        $fullName = [];

        $firstName = data_get($name, 'first_name');
        if ( $firstName ) {
            $fullName[] = $firstName;
        }

        $middleName = data_get($name, 'middle_name');
        if ( $middleName ) {
            $fullName[] = $middleName;
        }

        $lastName = data_get($name, 'last_name');
        if ( $lastName ) {
            $fullName[] = $lastName;
        }

        return implode(' ', $fullName);
    }
}
