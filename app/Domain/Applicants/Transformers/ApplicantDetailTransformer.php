<?php


namespace App\Domain\Applicants\Transformers;


use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

class ApplicantDetailTransformer extends TransformerAbstract
{
    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    public function transform(Applicant $applicant): array
    {
        return [
            'id'                => $applicant->id,
            'is_submitted'      => (bool) optional($applicant->application)->is_submitted,
            'submission_number' => $applicant->application->submission_number ?? '',
            'status'            => $applicant->application->status ?? '',

            'dates'           => $this->getDates($applicant),
            'application_for' => $applicant->application->application_for ?? '',
            'location_first'  => [
                'district'     => $applicant->application ? $this->getLocationFormat(
                    $applicant->application->first_priority_district
                ) : '',
                'municipality' => $applicant->application ? $this->getLocationFormat(
                    $applicant->application->first_priority_municipality
                ) : '',
                'ward'         => $applicant->application ? data_get(
                    $applicant->application,
                    'locations.first.ward',
                    null
                ) : null,
            ],
            'personal'        => $this->getPersonal($applicant),
            'qualification'   => $this->getQualification($applicant),
        ];
    }

    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    protected function getPersonal(Applicant $applicant): array
    {
        return [
            'name_in_nepali'      => $applicant->name_in_nepali_formatted,
            'name_in_english'     => $applicant->name_in_english_formatted,
            'applicant_photo'     => data_get($applicant, "details.applicant_photo", []),
            'gender'              => data_get($applicant, "details.gender", ''),
            'ethnicity'           => data_get($applicant, "details.ethnicity", ''),
            'ethnicity_other'     => data_get($applicant, "details.ethnicity_other", ''),
            'mother_tongue'       => data_get($applicant, "details.mother_tongue", ''),
            'mother_tongue_other' => data_get($applicant, "details.mother_tongue_other", ''),
            'disability'          => $applicant->disability_formatted,
            'dob_bs'              => $applicant->dob_bs,
            'dob_ad'              => data_get($applicant, "details.dob_ad", ''),
            'citizenship'         => $this->getCitizenship($applicant),
            'addresses'           => $this->getAddresses($applicant),
            'mobile_number'       => data_get($applicant, "mobile_number", ''),
            'father_name'         => $applicant->father_name_formatted,
            'grand_father_name'   => $applicant->grand_father_name_formatted,
            'mother_name'         => $applicant->mother_name_formatted,
            'spouse_name'         => $applicant->spouse_name_formatted,
        ];
    }

    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    protected function getAddresses(Applicant $applicant): array
    {
        return [
            'permanent_address'         => [
                'district'     => $applicant->permanent_address_district ? $this->getLocationFormat(
                    $applicant->permanent_address_district
                ) : '',
                'municipality' => $applicant->permanent_address_municipality ? $this->getLocationFormat(
                    $applicant->permanent_address_municipality
                ) : '',
                'ward'         => data_get($applicant->permanent_address, "ward", ''),
                'tole'         => data_get($applicant->permanent_address, "tole", ''),
            ],
            'has_current_address'       => (bool) optional($applicant->details)->has_current_address,
            'current_address'           => [
                'district'     => $applicant->current_address_district ? $this->getLocationFormat(
                    $applicant->current_address_district
                ) : '',
                'municipality' => $applicant->current_address_municipality ? $this->getLocationFormat(
                    $applicant->current_address_municipality
                ) : '',
                'ward'         => data_get($applicant->current_address, "ward", ''),
                'tole'         => data_get($applicant->temporary_address, "tole", ''),
            ],
            'current_address_documents' => data_get($applicant->details, "current_address_documents", []),
        ];
    }

    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    protected function getCitizenship(Applicant $applicant): array
    {
        return [
            'number'          => data_get($applicant, "citizenship_number", ''),
            'issued_district' => $applicant->citizenship_issued_district ? $this->getLocationFormat(
                $applicant->citizenship_issued_district
            ) : '',
            'issued_date'     => data_get($applicant, "citizenship_issued_date_bs", ''),
            'files'           => data_get($applicant, "details->citizenship_files", []),
        ];
    }

    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    protected function getQualification(Applicant $applicant): array
    {
        return [
            'has_education_qualification'    => (bool) (data_get($applicant->details, "qualification.has_education_qualification")),
            'files_for_supervisor_education' => data_get($applicant->details, "files_for_supervisor_education", []),
            'files_for_enumerator_education' => data_get($applicant->details, "files_for_enumerator_education", []),
            'files_for_extra_education'      => data_get($applicant->details, "files_for_extra_education", []),
            'education'                      => [
                'supervisor' => [
                    'files'               => [],
                    'grading_system'      => data_get($applicant->details, "qualification.education.supervisor.grading_system", ApplicationConstants::GRADING_SYSTEM_GRADE),
                    'percentage'          => data_get($applicant->details, "qualification.education.supervisor.percentage", null),
                    'grade'               => data_get($applicant->details, "qualification.education.supervisor.grade", null),
                    'major_subject'       => data_get($applicant->details, "qualification.education.supervisor.major_subject", null),
                    'major_subject_other' => data_get($applicant->details, "qualification.education.supervisor.major_subject_other", null),
                ],
                'enumerator' => [
                    'files'               => [],
                    'grading_system'      => data_get($applicant->details, "qualification.education.enumerator.grading_system", ApplicationConstants::GRADING_SYSTEM_GRADE),
                    'percentage'          => data_get($applicant->details, "qualification.education.enumerator.percentage", null),
                    'grade'               => data_get($applicant->details, "qualification.education.enumerator.grade", null),
                    'major_subject'       => data_get($applicant->details, "qualification.education.enumerator.major_subject", null),
                    'major_subject_other' => data_get($applicant->details, "qualification.education.enumerator.major_subject_other", null),
                ],
                'extra'      => [
                    'major_subject'       => data_get($applicant->details, "qualification.education.extra.major_subject", null),
                    'major_subject_other' => data_get($applicant->details, "qualification.education.extra.major_subject_other", null),
                ],
            ],
            'has_experience'                 => (bool) (data_get($applicant->details, "qualification.has_experience")),
            'experience_documents'           => data_get($applicant->details, "experience_documents", []),
            'experience'                     => [
                'organization' => data_get($applicant->details, "qualification.experience.organization", ''),
                'period_day'   => data_get($applicant->details, "qualification.experience.period_day", ''),
                'period_month' => data_get($applicant->details, "qualification.experience.period_month", ''),
                'files'        => [],
            ],
        ];
    }

    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    protected function getDates(Applicant $applicant): array
    {
        return [
            'created_at'     => Helper::dateResponse($applicant->created_at),
            'updated_at'     => Helper::dateResponse($applicant->updated_at),
            'submitted_at'   => $this->getSubmittedAtDate($applicant),
            'last_date'      => Helper::dateResponse($applicant->last_date),
            'remaining_days' => $applicant->remaining_days ?? '',
        ];
    }

    /**
     * @param District|Municipality|null $location
     *
     * @return string
     */
    protected function getLocationFormat($location): string
    {
        if ( !$location ) {
            return '';
        }
        $locale = app()->getLocale();

        return $locale === 'en' ? $location->title_en : $location->title_ne;
    }

    /**
     * @param Applicant $applicant
     *
     * @return array|null
     */
    private function getSubmittedAtDate(Applicant $applicant): ?array
    {
        if ( !$applicant->application ) {
            return null;
        }

        if ( !$applicant->application->application_date ) {
            return null;
        }

        return Helper::dateResponse($applicant->application->application_date);
    }
}
