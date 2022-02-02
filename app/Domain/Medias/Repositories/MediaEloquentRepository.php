<?php

namespace App\Domain\Medias\Repositories;

use App\Domain\Medias\Models\Media;
use App\Infrastructure\Support\Repository;

/**
 * Class MediaEloquentRepository
 * @package App\Domain\Medias\Repositories
 */
class MediaEloquentRepository extends Repository implements MediaRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Media::class;
    }
}
