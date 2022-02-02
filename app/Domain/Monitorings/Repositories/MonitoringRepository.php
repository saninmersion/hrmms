<?php

namespace App\Domain\Monitorings\Repositories;

use App\Infrastructure\Support\Contracts\RepositoryInterface;

/**
 * Interface MonitoringRepository
 * @package App\Domain\Monitorings\Repositories
 */
interface MonitoringRepository extends RepositoryInterface
{
    /**
     * @param int $monitoredBy
     *
     * @return $this
     */
    public function byMonitoredBy(int $monitoredBy): self;

    /**
     * @param int $enteredBy
     *
     * @return $this
     */
    public function byEnteredBy(int $enteredBy): self;

    /**
     * @param int $districtCode
     *
     * @return $this
     */
    public function byDistrict(int $districtCode): self;

    /**
     * @param string $type
     *
     * @return $this
     */
    public function byMonitoringType(string $type): self;

    /**
     * @param string $formType
     *
     * @return $this
     */
    public function byFormType(string $formType): self;

    /**
     * @param int $districtCode
     *
     * @return array
     */
    public function countByFormForDistrict(int $districtCode): array;

    /**
     * @param int $districtCode
     *
     * @return array
     */
    public function countByFormAndMunicipalityForDistrict(int $districtCode): array;

    /**
     * @return array
     */
    public function countByFormAndDistrict(): array;

    /**
     * @return array
     */
    public function countByFormAndMunicipality(): array;
}
