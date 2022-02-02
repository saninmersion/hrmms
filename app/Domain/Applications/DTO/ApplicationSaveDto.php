<?php

namespace App\Domain\Applications\DTO;

use App\Infrastructure\Support\DTO\AbstractDto;
use App\Infrastructure\Support\DTO\DtoInterface;
use Illuminate\Support\Arr;

/**
 * Class ApplicationSaveDto
 * @package App\Domain\Applications\DTO
 */
class ApplicationSaveDto extends AbstractDto implements DtoInterface
{
    /**
     * @var string
     */
    public string $applicationFor;
    /**
     * @var array
     */
    public array $locations;

    /**
     * @param array $data
     */
    protected function map(array $data): void
    {
        $this->applicationFor = $data['application_for'];

        $locations = [];
        foreach (Arr::get($data, 'application_locations', []) as $location) {
            $locations[] = [
                'priority'         => $location['priority'] ?? 0,
                'district'         => $location['district'] ?? null,
                'local_government' => $location['local_government'] ?? null,
                'ward'             => $location['ward'] ?? null,
            ];
        }
        $this->locations = $locations;
    }

    /**
     * @return string[]
     */
    protected function validationRules(): array
    {
        return [
            'application_for'       => 'nullable',
            'application_locations' => 'nullable',
        ];
    }
}
