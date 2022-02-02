<?php

namespace App\Domain\Users\Requests;

use App\Domain\Users\DTO\UserUpdateDto;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Requests\WebFormRequest;
use Illuminate\Routing\Route;

/**
 * Class UserUpdateRequest
 * @package App\Domain\Users\Requests
 */
class UserUpdateRequest extends WebFormRequest
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
        $districtTable     = DBTables::DISTRICTS;
        $roles             = implode(',', AuthRoles::assignable());
        $staffDistrictRole = AuthRoles::DISTRICT_STAFFS;

        /** @var Route $route */
        $route  = $this->route();
        $userId = $route->parameter('user_id');

        return [
            'email'         => "required|email|max:200|unique:{$userTable},email,{$userId}",
            'name'          => "required|string|max:200",
            'role'          => "required|string|in:{$roles}",
            'username'      => "required|string|unique:{$userTable},username,".$userId,
            'district'      => "nullable|required_if:role,{$staffDistrictRole},".AuthRoles::SUPERVISOR."|exists:{$districtTable},code",
            'census_office' => "nullable|required_if:role,".AuthRoles::SUPERVISOR,
        ];
    }

    /**
     *
     * @return UserUpdateDto
     */
    public function formData(): UserUpdateDto
    {
        return new UserUpdateDto($this->validated());
    }
}
