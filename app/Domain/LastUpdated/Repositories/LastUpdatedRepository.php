<?php

namespace App\Domain\LastUpdated\Repositories;

use App\Domain\LastUpdated\Models\LastUpdated;
use App\Infrastructure\Support\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface LastUpdatedRepository
 * @package App\Domain\LastUpdated\Repositories
 */
interface LastUpdatedRepository extends RepositoryInterface
{
    /**
     * @param string $name
     *
     * @return LastUpdated|array
     * @throws ModelNotFoundException
     */
    public function getByName(string $name);
}
