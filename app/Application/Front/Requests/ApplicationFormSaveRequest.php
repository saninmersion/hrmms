<?php

namespace App\Application\Front\Requests;

use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Rules\Age;
use App\Infrastructure\Support\Rules\MobileNumber;
use App\Infrastructure\Support\Rules\NepaliDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class ApplicationFormCreateRequest
 * @package App\Application\Front\Requests
 */
class ApplicationFormSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $applicationTypes = implode(',', ApplicationType::all());
        $districtTable    = DBTables::DISTRICTS;
        $applicantTable   = DBTables::AUTH_APPLICANTS;
        $genders          = implode(',', ApplicationConstants::genders());

        /** @var Applicant $currentApplicant */
        $currentApplicant = AuthHelper::currentUser(Guard::APPLICANT);

        $district = $this->input('personal.citizenship_issued_district');
        $gender   = $this->input('personal.gender');

        return [
            'application.application_for'          => "required|in:{$applicationTypes}",
            'personal.gender'                      => "required|in:{$genders}",
            'personal.dob_bs'                      => ['required', new NepaliDate()],
            'personal.dob_ad'                      => ['required', 'date', new Age($gender)],
            'personal.citizenship_number'          => [
                'required',
                'string',
                'max:255',
                Rule::unique($applicantTable, 'citizenship_number')->where(
                    fn($query) => $query->where('citizenship_issued_district_code', $district)
                )->ignore($currentApplicant->id),
            ],
            'personal.citizenship_issued_district' => "required|exists:{$districtTable},code",
            'personal.mobile_number'               => [
                'required',
                new MobileNumber(),
                Rule::unique($applicantTable, 'mobile_number')->ignore($currentApplicant->id),
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'personal.citizenship_issued_district' => trans('application.fields.citizenship_issued_district'),
            'application.application_for'          => trans('application.preview.application_position'),
            'personal.gender'                      => trans('application.fields.gender'),
            'personal.dob_bs'                      => trans('application.fields.dob'),
            'personal.dob_ad'                      => trans('application.fields.dob'),
            'personal.citizenship_number'          => trans('application.fields.citizenship_no'),
            'personal.mobile_number'               => trans('application.fields.mobile_number'),
        ];
    }

    /**
     * @return array
     */
    public function prepare(): array
    {
        /** @var Applicant $applicant */
        $applicant = $this->user();

        $applications = $this->prepareApplicationData();

        $applicant = $this->prepareApplicantVitals($applicant);
        $applicant = $this->prepareApplicantPersonal($applicant);
        $applicant = $this->prepareApplicantAddress($applicant);
        $applicant = $this->prepareApplicantThreeGeneration($applicant);
        $applicant = $this->prepareApplicantEducation($applicant);
        $applicant = $this->prepareApplicantTrainingExp($applicant);

        return [$applicant, $applications];
    }

    /**
     * @param Applicant $applicant
     *
     * @return Applicant
     */
    protected function prepareApplicantVitals(Applicant $applicant): Applicant
    {
        $details = json_decode(json_encode($applicant->details), true);

        if ( $this->has('personal.gender') ) {
            $details['gender'] = $this->input('personal.gender');
        }

        if ( $this->has('personal.dob_bs') ) {
            $applicant->dob_bs = $this->input('personal.dob_bs');
        }

        if ( $this->has('personal.dob_ad') ) {
            $details['dob_ad'] = $this->input('personal.dob_ad');
        }

        if ( $this->has('personal.citizenship_number') ) {
            $applicant->citizenship_number = $this->input('personal.citizenship_number');
        }

        if ( $this->has('personal.citizenship_issued_district') ) {
            $applicant->citizenship_issued_district_code = $this->input('personal.citizenship_issued_district');
        }

        if ( $this->has('personal.citizenship_issued_date') ) {
            $applicant->citizenship_issued_date_bs = $this->input('personal.citizenship_issued_date');
        }

        if ( $this->has('personal.mobile_number') ) {
            $applicant->mobile_number = $this->input('personal.mobile_number');
        }

        $applicant->details = $details;

        return $applicant;
    }

    /**
     * @param Applicant $applicant
     *
     * @return Applicant
     */
    protected function prepareApplicantPersonal(Applicant $applicant): Applicant
    {
        $details = json_decode(json_encode($applicant->details), true);

        if ( $this->has('personal.name_in_nepali') ) {
            $details['name_in_nepali'] = $this->input('personal.name_in_nepali');
        }

        if ( $this->has('personal.name_in_english') ) {
            $details['name_in_english'] = $this->input('personal.name_in_english');
        }

        if ( $this->has('personal.applicant_photo') ) {
            $details['applicant_photo'] = $this->input('personal.applicant_photo');
        }

        if ( $this->has('personal.citizenship_files') ) {
            $details['citizenship_files'] = $this->input('personal.citizenship_files');
        }

        $details['ethnicity']           = $this->input('personal.ethnicity');
        $details['ethnicity_other']     = $this->input('personal.ethnicity_other');
        $details['mother_tongue']       = $this->input('personal.mother_tongue');
        $details['mother_tongue_other'] = $this->input('personal.mother_tongue_other');
        $details['disability']          = $this->input('personal.disability');

        $applicant->details = $details;

        return $applicant;
    }

    /**
     * @param Applicant $applicant
     *
     * @return Applicant
     */
    protected function prepareApplicantAddress(Applicant $applicant): Applicant
    {
        $details = json_decode(json_encode($applicant->details), true);

        if ( $this->has('personal.permanent_address') ) {
            $applicant->permanent_address = $this->input('personal.permanent_address');
        }

        if ( $this->has('personal.temporary_address') ) {
            $applicant->temporary_address = $this->input('personal.temporary_address');
        }

        if ( $this->has('personal.has_current_address') ) {
            $details['has_current_address'] = $this->boolean('personal.has_current_address');
        }

        if ( $this->has('personal.current_address') ) {
            $applicant->current_address = $this->input('personal.current_address');
        }

        if ( $this->has('personal.current_address_documents') ) {
            $details['current_address_documents'] = $this->input('personal.current_address_documents');
        }

        $applicant->details = $details;

        return $applicant;
    }

    /**
     * @param Applicant $applicant
     *
     * @return Applicant
     */
    protected function prepareApplicantThreeGeneration(Applicant $applicant): Applicant
    {
        $details = json_decode(json_encode($applicant->details), true);

        if ( $this->has('personal.mother_name') ) {
            $details['mother_name'] = $this->input('personal.mother_name');
        }

        if ( $this->has('personal.father_name') ) {
            $details['father_name'] = $this->input('personal.father_name');
        }

        if ( $this->has('personal.grand_father_name') ) {
            $details['grand_father_name'] = $this->input('personal.grand_father_name');
        }

        if ( $this->has('personal.spouse_name') ) {
            $details['spouse_name'] = $this->input('personal.spouse_name');
        }

        $applicant->details = $details;

        return $applicant;
    }

    /**
     * @param Applicant $applicant
     *
     * @return Applicant
     */
    protected function prepareApplicantEducation(Applicant $applicant): Applicant
    {
        $details        = json_decode(json_encode($applicant->details), true);
        $qualifications = $details['qualification'] ?? [];

        if ( $this->has('qualification.has_education_qualification') ) {
            $qualifications['has_education_qualification'] = $this->boolean(
                'qualification.has_education_qualification'
            );
        }

        if ( $this->has('qualification.education') ) {
            $qualifications['education'] = $this->input('qualification.education');
        }

        if ( $this->has('qualification.files_for_supervisor_education') ) {
            $details['files_for_supervisor_education'] = $this->input('qualification.files_for_supervisor_education');
        }

        if ( $this->has('qualification.files_for_enumerator_education') ) {
            $details['files_for_enumerator_education'] = $this->input('qualification.files_for_enumerator_education');
        }

        if ( $this->has('qualification.files_for_extra_education') ) {
            $details['files_for_extra_education'] = $this->input('qualification.files_for_extra_education');
        }

        $details['qualification'] = $qualifications;
        $applicant->details       = $details;

        return $applicant;
    }

    /**
     * @param Applicant $applicant
     *
     * @return Applicant
     */
    protected function prepareApplicantTrainingExp(Applicant $applicant): Applicant
    {
        $details        = json_decode(json_encode($applicant->details), true);
        $qualifications = $details['qualification'] ?? [];

        if ( $this->has('qualification.has_training') ) {
            $qualifications['has_training'] = $this->boolean('qualification.has_training');
        }

        if ( $this->has('qualification.training') ) {
            $qualifications['training'] = $this->input('qualification.training');
        }

        if ( $this->has('qualification.training_documents') ) {
            $details['training_documents'] = $this->input('qualification.training_documents');
        }

        if ( $this->has('qualification.has_experience') ) {
            $qualifications['has_experience'] = $this->boolean('qualification.has_experience');
        }

        if ( $this->has('qualification.experience') ) {
            $qualifications['experience'] = $this->input('qualification.experience');
        }

        if ( $this->has('qualification.experience_documents') ) {
            $details['experience_documents'] = $this->input('qualification.experience_documents');
        }

        $details['qualification'] = $qualifications;
        $applicant->details       = $details;

        return $applicant;
    }

    /**
     * @return array
     */
    protected function prepareApplicationData(): array
    {
        $data = [];

        if ( $this->input('application.application_for') ) {
            $data['for_supervisor'] = in_array(
                $this->input('application.application_for'),
                [ApplicationType::SUPERVISOR, ApplicationType::ENUMERATOR_SUPERVISOR]
            );

            $data['for_enumerator'] = in_array(
                $this->input('application.application_for'),
                [ApplicationType::ENUMERATOR, ApplicationType::ENUMERATOR_SUPERVISOR]
            );
        }

        if ( $this->input('application.locations') ) {
            $key              = ['first', 'second', 'third'];
            $locations        = $this->input('application.locations');
            $locationData     = [];
            $duplicationCodes = [];

            foreach ($locations as $location) {
                $duplicationCode = sprintf(
                    '%s-%s',
                    $location['municipality'] ?? '',
                    $location['ward'] ?? ''
                );
                if ( in_array($duplicationCode, $duplicationCodes) ) {
                    continue;
                }

                $duplicationCodes[]         = $duplicationCode;
                $locationKey                = $key[(int) ($location['priority']) - 1] ?? $location['priority'];
                $locationData[$locationKey] = $location;
            }

            $data['locations'] = $locationData;
        }

        return $data;
    }
}
