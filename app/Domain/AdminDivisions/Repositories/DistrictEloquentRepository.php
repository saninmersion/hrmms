<?php

namespace App\Domain\AdminDivisions\Repositories;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\RedisCache\DistrictsRedisCache;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class DistrictEloquentRepository
 * @package App\Domain\AdminDivisions\Repositories
 */
class DistrictEloquentRepository extends BaseRepository implements DistrictRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return District::class;
    }

    /**
     * @return Collection
     */
    public function allDistrictForOptions(): Collection
    {
        /** @var DistrictsRedisCache $cacheService */
        $cacheService = app(DistrictsRedisCache::class);

        $districts = $cacheService->getAllDistricts();

        if ( $districts->isNotEmpty() ) {
            return $districts;
        }

        return $cacheService->cacheAllDistrict($this->all());
    }

    /**
     * @return Collection
     */
    public function allDistrictsWithMunicipalitiesOptions(): Collection
    {
        /** @var DistrictsRedisCache $cacheService */
        $cacheService = app(DistrictsRedisCache::class);

        $districts = $cacheService->getAllDistrictsWithMunicipalities();

        if ( $districts->isNotEmpty() ) {
            return $districts;
        }

        $districts = $this->with('municipalities')->findWhere(['is_old_district'=>false]);

        return $cacheService->cacheAllDistrictWithMunicipalities($districts);
    }

    /**
     * @param int $districtCode
     *
     * @return array|null
     */
    public function getByDistrictCode(int $districtCode): ?array
    {
        /** @var DistrictsRedisCache $cacheService */
        $cacheService = app(DistrictsRedisCache::class);

        return $cacheService->getByDistrictCode($districtCode);
    }
}
