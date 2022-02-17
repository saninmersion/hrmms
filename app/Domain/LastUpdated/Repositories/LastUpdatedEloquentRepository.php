<?php

namespace App\Domain\LastUpdated\Repositories;

use App\Domain\LastUpdated\Models\LastUpdated;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class LastUpdatedEloquentRepository
 *
 * @package App\Domain\LastUpdated\Repositories
 */
class LastUpdatedEloquentRepository extends Repository implements LastUpdatedRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return LastUpdated::class;
    }

    /**
     * @param string $name
     *
     * @return LastUpdated|array
     * @throws ModelNotFoundException
     */
    public function getByName(string $name)
    {
        $query = $this->getBuilder();

        $data = $query->where('updated_name', $name)->get();

        return $this->parserResult($data->first());
    }
}
