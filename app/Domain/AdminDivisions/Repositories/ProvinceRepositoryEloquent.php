<?php

namespace App\Domain\AdminDivisions\Repositories;

use App\Domain\AdminDivisions\Models\Province;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProvinceRepositoryEloquent
 * @package App\Domain\AdminDivisions\Repositories
 */
class ProvinceRepositoryEloquent extends BaseRepository implements ProvinceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Province::class;
    }
}
