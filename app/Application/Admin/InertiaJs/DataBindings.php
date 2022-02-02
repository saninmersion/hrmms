<?php

namespace App\Application\Admin\InertiaJs;

/**
 * Trait DataBindings
 * @package App\Application\Admin\InertiaJs
 */
trait DataBindings
{
    /**
     * @var array|string[]
     */
    public array $dataSharableClasses = [
        AppData::class,
        FlashMessages::class,
        AuthUser::class,
        Policy::class,
        Notifications::class,
    ];
}
