<?php


namespace App\Domain\Applications\Requests;


use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\Ethnicities;
use App\Infrastructure\Constants\MotherTongues;
use App\Infrastructure\Constants\StatusTypes;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\WebFormRequest;
use App\Infrastructure\Support\Rules\Age;
use App\Infrastructure\Support\Rules\MobileNumber;
use App\Infrastructure\Support\Rules\NepaliDate;
use Illuminate\Validation\Rule;

/**
 * Class OfflineApplicationRequest
 * @package App\Domain\Applications\Requests
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class OfflineApplicationRequest extends WebFormRequest
{

    /**
     * @var mixed
     */
    private $applicantRepository;

    public function __construct(ApplicantRepository $applicantRepository)
    {
        $this->applicantRepository = $applicantRepository;
    }

    public function rules(): array
    {
        $applicantId      = (int) $this->route('applicant_id');
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

        $gender                    = $this->input('personal.gender');
        $citizenshipIssuedDistrict = $this->input('personal.citizenship_issued_district');

        $rules = [
            'application.application_for'          => "required|in:{$applicationTypes}",
            'application.locations.*.district'     => "required|exists:{$districtTable},code",
            'application.locations.*.municipality' => "required|exists:{$municipalityTable},code",
            'application.locations.*.ward'         => "required",

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
                Rule::unique($applicantTable, 'mobile_number')->ignore($applicantId),
            ],
            'personal.ethnicity'           => "required|in:{$ethnicities}",
            'personal.ethnicity_other'     => "required_if:personal.ethnicity,{$ethnicityOther}",
            'personal.mother_tongue'       => "required|in:{$languages}",
            'personal.mother_tongue_other' => "required_if:personal.mother_tongue,{$languageOther}",
            'personal.disability'          => "required|in:{$disabilities}",

            'personal.citizenship_number'          => [
                'required',
                Rule::unique($applicantTable, 'citizenship_number')->where(
                    fn($query) => $query->where('citizenship_issued_district_code', $citizenshipIssuedDistrict)
                )->ignore($applicantId),
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

        $rules = $this->qualificationRules($rules);

        $rules = [];

        return $rules;
    }

    /**
     * @return array
     */
    public function prepare(): array
    {
        /** @var Applicant $applicant */
        $applicant = $this->applicantRepository->find((int) $this->route('applicant_id'));

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
        /** @var User $currentUser */
        $currentUser = AuthHelper::currentUser();

        $data['is_offline']    = true;
        $data['entered_by_id'] = $currentUser->id;

        $data['status'] = StatusTypes::APPLICATION_SUBMITTED;

        return $data;
    }

    /**
     * @param array $rules
     *
     * @return array
     */
    protected function qualificationRules(array $rules): array
    {
        $majorSubjects = implode(',', ApplicationConstants::majorSubjects());

        $rules = array_merge(
            $rules,
            [
                'qualification.has_education_qualification'         => "required",
                'qualification.education.extra.major_subject'       => "nullable|in:{$majorSubjects}",
                'qualification.education.extra.major_subject_other' => "required_if:qualification.education.extra.major_subject,".ApplicationConstants::MAJOR_SUBJECT_OTHERS,

                'qualification.experience.organization' => "required_if:qualification.has_experience,".true,
                'qualification.experience.period_day'   => "required_if:qualification.has_experience,".true,
                'qualification.experience.period_month' => "required_if:qualification.has_experience,".true,
                'qualification.experience_documents'    => "required_if:qualification.has_experience,".true,
            ]
        );

        $hasEducation   = $this->input('qualification.has_education_qualification');
        $applicationFor = $this->input('application.application_for');

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
}
