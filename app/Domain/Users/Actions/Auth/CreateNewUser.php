<?php

namespace App\Domain\Users\Actions\Auth;

use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\UserStatus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * Class CreateNewUser
 * @package App\Domain\Users\Actions\Auth
 */
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * @var UserRepository
     */
    protected UserRepository $repository;

    /**
     * CreateUser constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param array $input
     *
     * @return User
     * @throws ValidationException
     */
    public function create(array $input)
    {
        $userTable = DBTables::AUTH_USERS;
        Validator::make(
            $input,
            [
                'name'     => "required|string|max:200",
                'email'    => "required|email|max:200|unique:{$userTable},email",
                'username' => "required|string|unique:{$userTable},username",
                'password' => $this->passwordRules(),
            ]
        )->validate();

        $user = $this->repository->create(
            [
                'name'     => $input['name'],
                'email'    => $input['email'],
                'username' => $input['username'],
                'password' => $input['password'],
                'role'     => AuthRoles::MONITORING,
                'status'   => UserStatus::STATUS_INACTIVE,
            ]
        );

        session()->flash('status', "User created. You will be able to login when approved.");

        return $user;
    }
}
