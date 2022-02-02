<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\DTO\UserChangePasswordDto;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;

/**
 * Class ChangeUserPassword
 * @package App\Domain\Users\Actions
 */
class ChangeUserPassword
{
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
     * @param UserChangePasswordDto $data
     * @param int                   $userId
     *
     * @return User
     */
    public function change(UserChangePasswordDto $data, int $userId): User
    {
        return $this->repository->update(
            [
                'password' => $data->password,
            ],
            $userId
        );
    }
}
