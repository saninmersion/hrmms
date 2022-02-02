<?php

namespace App\Infrastructure\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class RequestCriteria
 * @package App\Infrastructure\Support
 */
class RequestCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    protected array $filters;

    /**
     * BaseRequestCriteria constructor.
     *
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * @param Builder             $model
     * @param RepositoryInterface $repository
     *
     * @return Builder
     * @SuppressWarnings("unused")
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function apply($model, RepositoryInterface $repository)
    {
        /** @var Builder $model */
        if ( method_exists($this, "preQuery") ) {
            $model = $this->preQuery($model, $this->filters);
        }

        collect($this->filters)->each(
            function ($value, $key) use (&$model) {
                $key    = (string) Str::of($key)->camel();
                $method = "{$key}Filter";

                if ( method_exists($this, $method) ) {
                    $model = $this->$method($model, $value ?? null);
                }
            }
        );

        if ( method_exists($this, "postQuery") ) {
            $model = $this->postQuery($model, $this->filters);
        }

        return $model;
    }
}
