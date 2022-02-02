<?php


namespace App\Domain\Applicants\DTO;


use App\Infrastructure\Support\DTO\AbstractDto;
use App\Infrastructure\Support\DTO\DtoInterface;
use Illuminate\Support\Arr;

/**
 * Class GenerateContractDTO
 * @package App\Domain\Applicants\DTO
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class GenerateContractDTO extends AbstractDto implements DtoInterface
{
    /**
     * @var string
     */
    public string $permanentAddressDistrict;

    /**
     * @var string
     */
    public string $permanentAddressLocalLevel;

    /**
     * @var string
     */
    public string $permanentAddressWard;

    /**
     * @var string
     */
    public string $currentAddressDistrict;

    /**
     * @var string
     */
    public string $currentAddressLocalLevel;

    /**
     * @var string
     */
    public string $currentAddressWard;

    /**
     * @var string
     */
    public string $grandfatherFullName;

    /**
     * @var string
     */
    public string $fatherFullName;

    /**
     * @var string
     */
    public string $gender;

    /**
     * @var string
     */
    public string $age;

    /**
     * @var string
     */
    public string $censusOffice;

    /**
     * @var string
     */
    public string $contractDay;

    /**
     * @var string
     */
    public string $contractMonth;

    /**
     * @var string
     */
    public string $contractYear;

    /**
     * @var string
     */
    public string $employeeName;

    /**
     * @var string
     */
    public string $employerName;

    /**
     * @var string
     */
    public string $employeeRole;

    /**
     * @inheritDoc
     */
    protected function map(array $data): void
    {
        $this->permanentAddressDistrict   = Arr::get($data, 'permanentAddressDistrict');
        $this->permanentAddressLocalLevel = Arr::get($data, 'permanentAddressLocalLevel');
        $this->permanentAddressWard       = Arr::get($data, 'permanentAddressWard');
        $this->currentAddressDistrict     = Arr::get($data, 'currentAddressDistrict');
        $this->currentAddressLocalLevel   = Arr::get($data, 'currentAddressLocalLevel');
        $this->currentAddressWard         = Arr::get($data, 'currentAddressWard');
        $this->grandfatherFullName        = Arr::get($data, 'grandfatherFullName');
        $this->fatherFullName             = Arr::get($data, 'fatherFullName');
        $this->gender                     = Arr::get($data, 'gender');
        $this->age                        = Arr::get($data, 'age');
        $this->censusOffice               = Arr::get($data, 'censusOffice');
        $this->contractDay                = Arr::get($data, 'contractDay');
        $this->contractMonth              = Arr::get($data, 'contractMonth');
        $this->contractYear               = Arr::get($data, 'contractYear');
        $this->employeeName               = Arr::get($data, 'employeeName');
        $this->employeeRole               = Arr::get($data, 'employeeRole');
    }

    /**
     * @inheritDoc
     */
    protected function validationRules(): array
    {
        return [];
    }
}
