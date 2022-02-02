<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\DTO\UserCreateDto;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;

/**
 * Class CreateUser
 * @package App\Domain\Users\Actions
 */
class CreateUser
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
     * @param UserCreateDto $data
     *
     * @return User
     */
    public function create(UserCreateDto $data): User
    {
        return $this->repository->create(
            [
                'name'             => $data->name,
                'email'            => $data->email,
                'username'         => $data->username,
                'password'         => $data->password,
                'role'             => $data->role,
                'district_code'    => $data->district,
                'census_office_id' => $data->censusOffice,
                'status'           => $data->status,
            ]
        );
    }
}
