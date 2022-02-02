<?php


namespace App\Infrastructure\Support\Requests;


use Illuminate\Foundation\Http\FormRequest;

abstract class ApiRequest extends FormRequest implements FormRequestInterface
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
