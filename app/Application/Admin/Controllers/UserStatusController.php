<?php


namespace App\Application\Admin\Controllers;


use App\Domain\Users\Actions\ChangeUserStatus;
use App\Infrastructure\Constants\UserStatus;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class UserStatusController extends AdminController
{

    /**
     * @param int              $userId
     * @param ChangeUserStatus $userStatus
     *
     * @return RedirectResponse
     */
    public function deactivateUser($userId, ChangeUserStatus $userStatus)
    {
        $userStatus->change(UserStatus::STATUS_INACTIVE, (int) $userId);

        session()->flash('success', 'User Deactivated');

        return redirect()->route('admin.users.index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param int              $userId
     * @param ChangeUserStatus $userStatus
     *
     * @return RedirectResponse
     */
    public function activateUser($userId, ChangeUserStatus $userStatus)
    {
        $userStatus->change(UserStatus::STATUS_ACTIVE, (int) $userId);

        return back()->with('status', 'User activated');
    }
}
