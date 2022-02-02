<?php

namespace App\Application\Api\Requests;

use App\Infrastructure\Support\Requests\ApiRequest;

/**
 * Class LoginRequest
 * @package App\Application\Api\Requests
 */
class LoginRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required',
        ];
    }
}
