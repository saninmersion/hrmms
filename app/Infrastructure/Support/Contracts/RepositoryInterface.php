<?php

namespace App\Infrastructure\Support\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\LazyCollection;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface as BaseRepositoryInterface;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Interface RepositoryInterface
 * @package App\Infrastructure\Support\Contracts
 */
interface RepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Push Criteria for filter the query
     *
     * @param CriteriaInterface $criteria
     *
     * @return $this
     * @throws RepositoryException
     */
    public function pushCriteria($criteria);

    /**
     * @return Builder|Model
     */
    public function getBuilder();

    /**
     * @return LazyCollection
     */
    public function cursor(): LazyCollection;

    /**
     * Retrieve all data of repository, paginated
     *
     * @param int|null $limit
     * @param array    $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*']);

    /**
     * @param int $value
     *
     * @return Builder
     */
    public function take(int $value);

    /**
     * @param string $relation
     *
     * @return Builder
     */
    public function doesntHave(string $relation);

    /**
     * @param string $relation
     *
     * @return Builder
     */
    public function has(string $relation);
}
