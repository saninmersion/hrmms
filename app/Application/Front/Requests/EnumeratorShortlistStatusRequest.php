<?php

namespace App\Application\Front\Requests;

use App\Infrastructure\Support\Rules\MobileNumber;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class EnumeratorShortlistStatusRequest
 *
 * @package App\Application\Front\Requests
 */
class EnumeratorShortlistStatusRequest extends FormRequest
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
            'mobile_number'     => ['required_without:submission_number', new MobileNumber()],
            'submission_number' => ['required_without:mobile_number', 'regex:/NPHC-\d{7}/'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'mobile_number'     => trans('application.mobile_number'),
            'submission_number' => trans('application.submission_number'),
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'submission_number.regex' => trans('validation.invalid_submission_number'),
        ];
    }
}
