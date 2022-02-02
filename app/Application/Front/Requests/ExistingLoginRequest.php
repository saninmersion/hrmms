<?php

namespace App\Application\Front\Requests;

use App\Infrastructure\Support\Rules\MobileNumber;
use App\Infrastructure\Support\Rules\NepaliDate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ExistingLoginRequest
 * @package App\Application\Front\Requests
 */
class ExistingLoginRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'dob'           => ['required', new NepaliDate()],
            'mobile_number' => ['required', new MobileNumber()],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'dob'           => trans('application.date_of_birth'),
            'mobile_number' => trans('application.mobile_number'),
        ];
    }
}
