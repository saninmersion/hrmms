<?php

namespace App\Application\Front\Requests;

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Rules\Gender;
use App\Infrastructure\Support\Rules\MobileNumber;
use App\Infrastructure\Support\Rules\NepaliDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class NewLoginRequest
 * @package App\Application\Front\Requests
 */
class NewLoginRequest extends FormRequest
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
        $applicantTable = DBTables::AUTH_APPLICANTS;
        $districtTable  = DBTables::DISTRICTS;
        $district       = $this->input('citizenship_district');

        return [
            'dob'                  => ['required', new NepaliDate()],
            'citizenship_number'   => [
                'required',
                'string',
                'max:255',
                Rule::unique($applicantTable, 'citizenship_number')->where(
                    fn($query) => $query->where('citizenship_issued_district_code', $district)
                ),
            ],
            'citizenship_district' => "required|exists:{$districtTable},code",
            'mobile_number'        => ['required', new MobileNumber(), "unique:{$applicantTable},mobile_number"],
            'gender'               => ['required', new Gender()],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'dob'                  => trans('application.date_of_birth'),
            'citizenship_number'   => trans('application.Nepali Citizenship No'),
            'citizenship_district' => trans('application.citizenship_district'),
            'mobile_number'        => trans('application.mobile_number'),
            'gender'               => trans('application.gender'),
        ];
    }
}
