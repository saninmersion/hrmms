<?php

namespace App\Application\Admin\InertiaJs;

use App\Infrastructure\Support\Contracts\InertiaDataSharable;

/**
 * Class AppData
 * @package App\Application\Admin\InertiaJs
 */
class AppData implements InertiaDataSharable
{
    /**
     * @return array
     */
    public function data()
    {
        return [
            'name'      => config('app.name'),
            'copyright' => config('config.copyright'),
            'developer' => config('config.developer'),
        ];
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return 'app';
    }
}
