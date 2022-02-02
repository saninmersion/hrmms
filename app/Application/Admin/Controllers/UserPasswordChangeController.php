<?php


namespace App\Application\Admin\Controllers;


use App\Domain\Users\Actions\ChangeUserPassword;
use App\Domain\Users\Requests\UserPasswordChangeRequest;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Http\RedirectResponse;

class UserPasswordChangeController extends AdminController
{

    /**
     * @param int                       $userId
     * @param UserPasswordChangeRequest $request
     * @param ChangeUserPassword        $userPassword
     *
     * @return RedirectResponse
     */
    public function changePassword($userId, UserPasswordChangeRequest $request, ChangeUserPassword $userPassword)
    {
        $userPassword->change($request->formData(), (int) $userId);

        return back()->with('status', 'password-updated');
    }
}
