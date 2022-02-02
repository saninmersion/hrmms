<?php

namespace App\Domain\Users\Requests;

use App\Domain\Users\DTO\UserChangePasswordDto;
use App\Infrastructure\Support\Requests\WebFormRequest;

/**
 * Class UserPasswordChangeRequest
 * @package App\Domain\Users\Requests
 */
class UserPasswordChangeRequest extends WebFormRequest
{
    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'password' => trans('admin-users.password'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required',
        ];
    }

    /**
     * @return UserChangePasswordDto
     */
    public function formData(): UserChangePasswordDto
    {
        return new UserChangePasswordDto($this->validated());
    }
}
