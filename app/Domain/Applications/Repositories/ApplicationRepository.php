<?php

namespace App\Domain\Applications\Repositories;

use App\Infrastructure\Support\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface ApplicationRepository
 * @package App\Domain\Applications\Repositories
 */
interface ApplicationRepository extends RepositoryInterface
{
    /**
     * @return Collection
     */
    public function getOverAllStats(): Collection;

    /**
     * @return Collection
     */
    public function getOverAllStatsByDistrict(): Collection;

    /**
     * @return Collection
     */
    public function getGenderBasedStats(): Collection;

    /**
     * @return Collection
     */
    public function getGenderBasedStatsByDistrict(): Collection;

    /**
     * @return Collection
     */
    public function getDailyStats(): Collection;

    /**
     * @param string $priority
     *
     * @return Collection
     */
    public function getDistrictsStats(string $priority = 'first'): Collection;

    /**
     * @param string $priority
     *
     * @return Collection
     */
    public function getMunicipalityStats(string $priority = 'first'): Collection;
}
