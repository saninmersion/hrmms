<?php

namespace App\Application\Admin\Controllers;

use App\Domain\Users\Models\User;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\Controller;
use Illuminate\Http\Request;

/**
 * Class NotificationsController
 * @package App\Application\Admin\Controllers
 */
class NotificationsController extends Controller
{
    /**
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        /** @var User $user */
        $user = AuthHelper::currentUser();

        $notifications = $user->unreadNotifications;

        return inertia(
            'Notifications/List',
            compact('notifications')
        );
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Request $request)
    {
        /** @var User $user */
        $user = AuthHelper::currentUser();

        $user->unreadNotifications->when(
            $request->input('id'),
            function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            }
        )->markAsRead();

        return redirect()->back();
    }
}
