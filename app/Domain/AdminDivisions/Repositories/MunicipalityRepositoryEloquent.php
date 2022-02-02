<?php

namespace App\Domain\AdminDivisions\Repositories;

use App\Domain\AdminDivisions\Models\Municipality;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class MunicipalityRepositoryEloquent
 * @package App\Domain\AdminDivisions\Repositories
 */
class MunicipalityRepositoryEloquent extends BaseRepository implements MunicipalityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Municipality::class;
    }
}
