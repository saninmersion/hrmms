<?php

namespace App\Domain\Applicants\DTO;

use App\Infrastructure\Support\DTO\AbstractDto;
use App\Infrastructure\Support\DTO\DtoInterface;
use Illuminate\Support\Arr;

/**
 * Class NewApplicantLoginAttemptDTO
 * @package App\Domain\Applicants\DTO
 */
class NewApplicantLoginAttemptDTO extends AbstractDto implements DtoInterface
{
    /**
     * @var string
     */
    public string $citizenship;

    /**
     * @var string
     */
    public string $dob;

    /**
     * @var int
     */
    public int $mobileNumber;

    /**
     * @var string
     */
    public string $gender;

    /**
     * @var int
     */
    public int $district;

    /**
     * @param array $data
     */
    protected function map(array $data): void
    {
        $this->dob          = Arr::get($data, 'dob');
        $this->citizenship  = Arr::get($data, 'citizenship_number');
        $this->district     = Arr::get($data, 'citizenship_district');
        $this->mobileNumber = Arr::get($data, 'mobile_number');
        $this->gender       = Arr::get($data, 'gender');
    }

    /**
     * @return string[]
     */
    protected function validationRules(): array
    {
        return [
            'dob'                  => 'required',
            'citizenship_number'   => "required",
            'citizenship_district' => "required",
            'mobile_number'        => 'required',
            'gender'               => 'required',
        ];
    }
}
