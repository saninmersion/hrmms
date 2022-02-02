<?php

namespace App\Domain\Applicants\DTO;

use App\Infrastructure\Support\DTO\AbstractDto;
use App\Infrastructure\Support\DTO\DtoInterface;
use Illuminate\Support\Arr;

/**
 * Class ApplicantLoginAttemptDTO
 * @package App\Domain\Applicants\DTO
 */
class ApplicantLoginAttemptDTO extends AbstractDto implements DtoInterface
{
    /**
     * @var string
     */
    public string $dob;

    /**
     * @var int
     */
    public int $mobileNumber;

    /**
     * @param array $data
     */
    protected function map(array $data): void
    {
        $this->dob          = Arr::get($data, 'dob');
        $this->mobileNumber = Arr::get($data, 'mobile_number');
    }

    /**
     * @return string[]
     */
    protected function validationRules(): array
    {
        return [
            'dob'           => 'required',
            'mobile_number' => 'required',
        ];
    }
}
