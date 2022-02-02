<?php

namespace App\Domain\Users\Requests;

use App\Domain\Users\DTO\UserCreateDto;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Requests\WebFormRequest;

/**
 * Class UserStoreRequest
 * @package App\Domain\Users\Requests
 */
class UserStoreRequest extends WebFormRequest
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name'          => trans('admin-users.full-name'),
            'email'         => trans('admin-users.email'),
            'username'      => trans('admin-users.username'),
            'password'      => trans('admin-users.password'),
            'role'          => trans('admin-users.role'),
            'district'      => trans('application.district'),
            'census_office' => trans('application.census_office'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $userTable         = DBTables::AUTH_USERS;
        $roles             = implode(',', AuthRoles::assignable());
        $staffDistrictRole = AuthRoles::DISTRICT_STAFFS;
        $districtTable     = DBTables::DISTRICTS;

        return [
            'name'          => "required|string|max:200",
            'email'         => "required|email|max:200|unique:{$userTable},email",
            'username'      => "required|string|unique:{$userTable},username",
            'password'      => 'required|string|max:50',
            'role'          => "required|string|in:{$roles}",
            'district'      => "nullable|required_if:role,{$staffDistrictRole},".AuthRoles::SUPERVISOR."|exists:{$districtTable},code",
            'census_office' => "nullable|required_if:role,".AuthRoles::SUPERVISOR,
        ];
    }

    /**
     * @return UserCreateDto
     */
    public function formData(): UserCreateDto
    {
        return new UserCreateDto($this->validated());
    }
}
