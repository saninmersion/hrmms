<?php

namespace App\Domain\CensusOffices\Repositories;

use App\Domain\CensusOffices\Models\CensusOffice;
use App\Infrastructure\Support\Repository;

/**
 * Class CensusOfficeEloquentRepository
 * @package App\Domain\CensusOffices\Repositories
 */
class CensusOfficeEloquentRepository extends Repository implements CensusOfficeRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return CensusOffice::class;
    }
}
