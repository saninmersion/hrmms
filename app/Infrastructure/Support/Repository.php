<?php

namespace App\Infrastructure\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\LazyCollection;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class Repository
 * @package App\Infrastructure\Support
 */
abstract class Repository extends BaseRepository
{
    /**
     * @return Builder|Model
     */
    public function getBuilder()
    {
        $this->applyCriteria();
        $this->applyScope();

        return $this->model;
    }

    /**
     * @return LazyCollection
     */
    public function cursor(): LazyCollection
    {
        return $this->getBuilder()->cursor();
    }

    /**
     * @param int $value
     *
     * @return Builder
     */
    public function take(int $value)
    {
        return $this->getBuilder()->take($value);
    }

    /**
     * @param string $relation
     *
     * @return Builder
     */
    public function doesntHave(string $relation)
    {
        return $this->getBuilder()->doesntHave($relation);
    }

    /**
     * @param string $relation
     *
     * @return Repository|Builder
     */
    public function has($relation)
    {
        return $this->getBuilder()->has($relation);
    }
}
