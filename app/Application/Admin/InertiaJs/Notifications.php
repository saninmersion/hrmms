<?php

namespace App\Application\Admin\InertiaJs;

use App\Domain\Users\Models\User;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Contracts\InertiaDataSharable;
use Closure;

/**
 * Class Notifications
 * @package App\Application\Admin\InertiaJs
 */
class Notifications implements InertiaDataSharable
{
    /**
     * @return Closure
     */
    public function data()
    {
        return function () {
            /** @var User $user */
            $user = AuthHelper::currentUser();

            return $user->unreadNotifications;
        };
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return 'notifications';
    }
}
