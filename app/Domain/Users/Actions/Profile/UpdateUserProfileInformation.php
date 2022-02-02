<?php

namespace App\Domain\Users\Actions\Profile;

use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\DBTables;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

/**
 * Class UpdateUserProfileInformation
 * @package App\Domain\Users\Actions\Profile
 */
class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param User  $user
     * @param array $input
     *
     * @return void
     * @throws ValidationException
     */
    public function update($user, array $input)
    {
        $userTable = DBTables::AUTH_USERS;

        Validator::make(
            $input,
            [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255', Rule::unique($userTable)->ignore($user->id)],
                'username' => ['required', 'max:255', Rule::unique($userTable)->ignore($user->id)],
                'photo'    => ['nullable', 'image', 'max:1024'],
            ]
        )->validateWithBag('updateProfileInformation');

        if ( isset($input['photo']) ) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ( $input['email'] !== $user->email && $user instanceof MustVerifyEmail ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill(
                [
                    'name'     => $input['name'],
                    'email'    => $input['email'],
                    'username' => $input['username'],
                ]
            )->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param User  $user
     * @param array $input
     *
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill(
            [
                'name'              => $input['name'],
                'email'             => $input['email'],
                'username'          => $input['username'],
                'email_verified_at' => null,
            ]
        )->save();

        $user->sendEmailVerificationNotification();
    }
}
