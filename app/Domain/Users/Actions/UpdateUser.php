<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\DTO\UserUpdateDto;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;

/**
 * Class UpdateUser
 * @package App\Domain\Users\Actions
 */
class UpdateUser
{
    /**
     * @var UserRepository
     */
    protected UserRepository $repository;

    /**
     * UpdateUser constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UserUpdateDto $data
     * @param int           $userId
     *
     * @return User
     */
    public function update(UserUpdateDto $data, int $userId): User
    {
        return $this->repository->update(
            [
                'name'             => $data->name,
                'email'            => $data->email,
                'username'         => $data->username,
                'role'             => $data->role,
                'district_code'    => $data->district,
                'census_office_id' => $data->censusOffice,
            ],
            $userId
        );
    }
}
