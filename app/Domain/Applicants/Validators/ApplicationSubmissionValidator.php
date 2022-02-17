<?php

namespace App\Domain\Applicants\Validators;

use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\Ethnicities;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Constants\MotherTongues;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Rules\Age;
use App\Infrastructure\Support\Rules\MobileNumber;
use App\Infrastructure\Support\Rules\NepaliDate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class ApplicationSubmissionValidator
 *
 * @package App\Domain\Applicants\Validators
 */
class ApplicationSubmissionValidator
{
    /**
     * @param array $inputs
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function makeValidator(array $inputs): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($inputs, $this->getRules($inputs), $this->getMessages(), $this->getAttributes());
    }

    /**
     * @param array $inputs
     *
     * @return array
     */
    protected function getRules(array $inputs): array
    {
        $applicationTypes = implode(',', ApplicationType::all());
        $genders          = implode(',', ApplicationConstants::genders());
        $ethnicities      = implode(',', Ethnicities::ethnicities());
        $ethnicityOther   = Ethnicities::ETHNICITY_OTHER;
        $languages        = implode(',', MotherTongues::motherTongues());
        $languageOther    = MotherTongues::LANGUAGE_OTHER;
        $disabilities     = implode(',', ApplicationConstants::disabilities());

        $districtTable     = DBTables::DISTRICTS;
        $municipalityTable = DBTables::MUNICIPALITIES;
        $applicantTable    = DBTables::AUTH_APPLICANTS;

        $gender                    = Arr::get($inputs, 'personal.gender');
        $citizenshipIssuedDistrict = Arr::get($inputs, 'personal.citizenship_issued_district');

        /** @var Applicant $currentApplicant */
        $currentApplicant = AuthHelper::currentUser(Guard::APPLICANT);

        $rules = [
            'application.application_for'          => "required|in:{$applicationTypes}",
            'application.locations.*.district'     => "required|exists:{$districtTable},code",
            'application.locations.*.municipality' => "required|exists:{$municipalityTable},code",

            'personal.name_in_nepali.first_name'  => "required",
            'personal.name_in_nepali.last_name'   => "required",
            'personal.name_in_english.first_name' => "required",
            'personal.name_in_english.last_name'  => "required",

            'personal.applicant_photo'     => "required|max:2048",
            'personal.gender'              => "required|in:{$genders}",
            'personal.dob_bs'              => ['required', new NepaliDate()],
            'personal.dob_ad'              => ['required', 'date', new Age($gender)],
            'personal.mobile_number'       => [
                'required',
                new MobileNumber(),
                Rule::unique($applicantTable, 'mobile_number')->ignore($currentApplicant->id),
            ],
            'personal.ethnicity'           => "required|in:{$ethnicities}",
            'personal.ethnicity_other'     => "required_if:personal.ethnicity,{$ethnicityOther}",
            'personal.mother_tongue'       => "required|in:{$languages}",
            'personal.mother_tongue_other' => "required_if:personal.mother_tongue,{$languageOther}",
            'personal.disability'          => "required|in:{$disabilities}",

            'personal.citizenship_number'          => [
                'required',
                Rule::unique($applicantTable, 'citizenship_number')->where(fn ($query) => $query->where('citizenship_issued_district_code', $citizenshipIssuedDistrict))->ignore($currentApplicant->id),
            ],
            'personal.citizenship_issued_district' => "required|exists:{$districtTable},code",
            'personal.citizenship_issued_date'     => ['required', new NepaliDate()],
            'personal.citizenship_files'           => "required",

            'personal.permanent_address.district'     => "required|exists:{$districtTable},code",
            'personal.permanent_address.municipality' => "required|exists:{$municipalityTable},code",
            'personal.permanent_address.ward'         => "required",

            'personal.current_address.district'     => "required_if:has_current_address,1".true."|nullable|exists:{$districtTable},code",
            'personal.current_address.municipality' => "required_if:has_current_address,1".true."|nullable|exists:{$municipalityTable},code",
            'personal.current_address.ward'         => "required_if:has_current_address,1",

            'personal.grand_father_name.first_name' => "required",
            'personal.grand_father_name.last_name'  => "required",
            'personal.father_name.first_name'       => "required",
            'personal.father_name.last_name'        => "required",
            'personal.mother_name.first_name'       => "required",
            'personal.mother_name.last_name'        => "required",
            'personal.spouse_name.first_name'       => "nullable",
            'personal.spouse_name.last_name'        => "nullable",
        ];

        $rules = $this->qualificationRules($rules, $inputs);

        return $rules;
    }

    /**
     * @param array $rules
     * @param array $inputs
     *
     * @return array
     */
    protected function qualificationRules(array $rules, array $inputs): array
    {
        $majorSubjects = implode(',', ApplicationConstants::majorSubjects());

        $rules = array_merge(
            $rules,
            [
                'qualification.has_education_qualification'         => "required|in:1",
                'qualification.education.extra.major_subject'       => "nullable|in:{$majorSubjects}",
                'qualification.education.extra.major_subject_other' => "required_if:qualification.education.extra.major_subject,".ApplicationConstants::MAJOR_SUBJECT_OTHERS,

                'qualification.experience.organization' => "required_if:qualification.has_experience,".true,
                'qualification.experience.period_day'   => "required_if:qualification.has_experience,".true,
                'qualification.experience.period_month' => "required_if:qualification.has_experience,".true,
                'qualification.experience_documents'    => "required_if:qualification.has_experience,".true,
            ]
        );

        $hasEducation   = Arr::get($inputs, 'qualification.has_education_qualification');
        $applicationFor = Arr::get($inputs, 'application.application_for');

        if ( $hasEducation && in_array($applicationFor, [ApplicationType::SUPERVISOR, ApplicationType::ENUMERATOR_SUPERVISOR]) ) {
            $rules = array_merge(
                $rules,
                [
                    'qualification.files_for_supervisor_education'           => "required",
                    'qualification.education.supervisor.grading_system'      => "required",
                    'qualification.education.supervisor.percentage'          => "required_if:qualification.education.supervisor.grading_system,".ApplicationConstants::GRADING_SYSTEM_PERCENTAGE,
                    'qualification.education.supervisor.grade'               => "required_if:qualification.education.supervisor.grading_system,".ApplicationConstants::GRADING_SYSTEM_GRADE,
                    'qualification.education.supervisor.major_subject'       => "required|in:{$majorSubjects}",
                    'qualification.education.supervisor.major_subject_other' => "required_if:qualification.education.supervisor.major_subject,".ApplicationConstants::MAJOR_SUBJECT_OTHERS,
                ]
            );
        }

        if ( $hasEducation && in_array($applicationFor, [ApplicationType::ENUMERATOR, ApplicationType::ENUMERATOR_SUPERVISOR]) ) {
            $rules = array_merge(
                $rules,
                [
                    'qualification.files_for_enumerator_education'      => "required",
                    'qualification.education.enumerator.grading_system' => "required",
                    'qualification.education.enumerator.percentage'     => "required_if:qualification.education.enumerator.grading_system,".ApplicationConstants::GRADING_SYSTEM_PERCENTAGE,
                    'qualification.education.enumerator.grade'          => "required_if:qualification.education.enumerator.grading_system,".ApplicationConstants::GRADING_SYSTEM_GRADE,
                ]
            );
        }

        return $rules;
    }

    /**
     * @return array
     */
    protected function getAttributes(): array
    {
        return [
            'application.application_for'          => trans('application.preview.application_position'),
            'application.locations.*.district'     => trans('application.fields.district'),
            'application.locations.*.municipality' => trans('application.fields.municipality'),
            'application.locations.*.ward'         => trans('application.fields.ward'),

            'personal.name_in_nepali.first_name'  => trans('application.fields.first_name'),
            'personal.name_in_nepali.last_name'   => trans('application.fields.last_name'),
            'personal.name_in_english.first_name' => trans('application.fields.first_name'),
            'personal.name_in_english.last_name'  => trans('application.fields.last_name'),

            'personal.applicant_photo'     => trans('application.fields.applicant_photo'),
            'personal.gender'              => trans('application.fields.gender'),
            'personal.dob_bs'              => trans('application.fields.dob'),
            'personal.dob_ad'              => trans('application.fields.dob'),
            'personal.mobile_number'       => trans('application.fields.mobile_number'),
            'personal.ethnicity'           => trans('application.fields.ethnicity'),
            'personal.ethnicity_other'     => trans('application.fields.ethnicity'),
            'personal.mother_tongue'       => trans('application.fields.mother_tongue'),
            'personal.mother_tongue_other' => trans('application.fields.mother_tongue'),
            'personal.disability'          => trans('application.fields.disability'),

            'personal.citizenship_number'          => trans('application.fields.citizenship_no'),
            'personal.citizenship_issued_district' => trans('application.fields.citizenship_issued_district'),
            'personal.citizenship_issued_date'     => trans('application.fields.citizenship_issued_date'),
            'personal.citizenship_files'           => trans('application.fields.citizenship_files'),

            'personal.permanent_address.district'     => trans('application.fields.district'),
            'personal.permanent_address.municipality' => trans('application.fields.municipality'),
            'personal.permanent_address.ward'         => trans('application.fields.ward'),
            'personal.permanent_address.tole'         => trans('application.fields.tole'),

            'personal.current_address.district'     => trans('application.fields.district'),
            'personal.current_address.municipality' => trans('application.fields.municipality'),
            'personal.current_address.ward'         => trans('application.fields.ward'),
            'personal.current_address.tole'         => trans('application.fields.tole'),
            'personal.current_address_documents'    => trans('application.fields.current_address_documents'),

            'personal.father_name.first_name'       => trans('application.fields.first_name'),
            'personal.father_name.last_name'        => trans('application.fields.last_name'),
            'personal.mother_name.first_name'       => trans('application.fields.first_name'),
            'personal.mother_name.last_name'        => trans('application.fields.last_name'),
            'personal.grand_father_name.first_name' => trans('application.fields.first_name'),
            'personal.grand_father_name.last_name'  => trans('application.fields.last_name'),
            'personal.spouse_name.first_name'       => trans('application.fields.first_name'),
            'personal.spouse_name.last_name'        => trans('application.fields.last_name'),

            'qualification.has_education_qualification'              => trans('application.fields.has_education_qualification'),
            'qualification.files_for_supervisor_education'           => trans('application.fields.files_for_supervisor_education'),
            'qualification.files_for_enumerator_education'           => trans('application.fields.files_for_enumerator_education'),
            'qualification.education.supervisor.grading_system'      => trans('application.fields.grading_system'),
            'qualification.education.supervisor.percentage'          => trans('application.fields.percentage'),
            'qualification.education.supervisor.grade'               => trans('application.fields.grade'),
            'qualification.education.supervisor.major_subject'       => trans('application.fields.major_subject'),
            'qualification.education.supervisor.major_subject_other' => trans('application.fields.major_subject'),
            'qualification.education.enumerator.grading_system'      => trans('application.fields.grading_system'),
            'qualification.education.enumerator.percentage'          => trans('application.fields.percentage'),
            'qualification.education.enumerator.grade'               => trans('application.fields.grade'),
            'qualification.education.enumerator.major_subject'       => trans('application.fields.major_subject'),
            'qualification.education.enumerator.major_subject_other' => trans('application.fields.major_subject'),
            'qualification.education.extra.major_subject_other'      => trans('application.fields.major_subject'),

            'qualification.experience.organization' => trans('application.fields.experience_organization'),
            'qualification.experience.period_day'   => trans('application.fields.experience_period'),
            'qualification.experience.period_month' => trans('application.fields.experience_period'),
            'qualification.experience_documents'    => trans('application.fields.experience_document'),
        ];
    }

    protected function getMessages(): array
    {
        return [
            'qualification.has_education_qualification.in' => trans('validation.required', ['attribute' => '']),
            'qualification.has_experience.in'              => trans('validation.required', ['attribute' => '']),
        ];
    }
}
