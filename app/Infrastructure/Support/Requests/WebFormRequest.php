<?php

namespace App\Infrastructure\Support\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class WebFormRequest
 * @package App\Infrastructure\Support\Requests
 */
abstract class WebFormRequest extends FormRequest implements FormRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
