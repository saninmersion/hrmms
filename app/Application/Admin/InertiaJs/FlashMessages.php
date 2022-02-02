<?php

namespace App\Application\Admin\InertiaJs;

use App\Infrastructure\Support\Contracts\InertiaDataSharable;
use Closure;

/**
 * Class FlashMessages
 * @package App\Application\Admin\InertiaJs
 */
class FlashMessages implements InertiaDataSharable
{
    /**
     * @return Closure
     */
    public function data()
    {
        return function () {
            return [
                'message' => session()->get('message'),
                'success' => session()->get('success'),
                'error'   => session()->get('error'),
                'warning' => session()->get('warning'),
                'info'    => session()->get('info'),
            ];
        };
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return 'flash';
    }
}
