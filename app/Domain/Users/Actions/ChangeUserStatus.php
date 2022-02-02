<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;

class ChangeUserStatus
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
     * @param string $status
     * @param int    $userId
     *
     * @return User
     */
    public function change(string $status, int $userId): User
    {
        return $this->repository->update(
            [
                'status' => $status,
            ],
            $userId
        );
    }
}
