<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;

/**
 * Class DeleteUser
 * @package App\Domain\Users\Actions
 */
class DeleteUser
{
    /**
     * @var UserRepository
     */
    protected UserRepository $repository;

    /**
     * DeleteUser constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Delete the given user.
     *
     * @param int $userId
     *
     * @return void
     */
    public function delete(int $userId): void
    {
        /** @var User $user */
        $user = $this->repository->find($userId);
        $user->deleteProfilePhoto();
        $this->repository->delete($userId);
    }
}
