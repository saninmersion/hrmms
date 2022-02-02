<?php

namespace App\Domain\AdminDivisions\Repositories;

use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface DistrictRepository
 * @package App\Domain\AdminDivisions\Repositories
 */
interface DistrictRepository extends RepositoryInterface
{
    /**
     * @return Collection
     */
    public function allDistrictForOptions(): Collection;

    /**
     * @return Collection
     */
    public function allDistrictsWithMunicipalitiesOptions(): Collection;

    /**
     * @param int $districtCode
     *
     * @return array|null
     */
    public function getByDistrictCode(int $districtCode): ?array;
}
