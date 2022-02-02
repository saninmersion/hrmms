<?php

namespace App\Infrastructure\Middleware;

use App\Infrastructure\Constants\General;
use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * Class HandleInertiaRequests
 * @package App\Infrastructure\Middleware
 */
class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'layouts.admin';

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @param Request $request
     *
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(
            parent::share($request),
            [
                'staticData' => [
                    'paginate_sizes' => General::paginateOptions(),
                ],
            ]
        );
    }
}
