<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Constants\ApplicationConstants;
use League\Fractal\TransformerAbstract;

/**
 * Class FrontFormTransformer
 *
 * @package App\Domain\Applicants\Transformers
 */
class FrontFormTransformer extends TransformerAbstract
{
    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    public function transform(Applicant $applicant): array
    {
        return [
            'id' => $applicant->id,

            'application' => [
                'application_for' => $applicant->application->application_for ?? "",
                'locations'       => $applicant->application->locations_array ?? [['priority' => 1]],
            ],

            'personal'      => $this->getPersonal($applicant),
            'qualification' => $this->getQualification($applicant),
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
            'name_in_nepali'              => [
                'first_name'  => data_get($applicant->details, "name_in_nepali.first_name", ''),
                'middle_name' => data_get($applicant->details, "name_in_nepali.middle_name", ''),
                'last_name'   => data_get($applicant->details, "name_in_nepali.last_name", ''),
            ],
            'name_in_english'             => [
                'first_name'  => data_get($applicant->details, "name_in_english.first_name", ''),
                'middle_name' => data_get($applicant->details, "name_in_english.middle_name", ''),
                'last_name'   => data_get($applicant->details, "name_in_english.last_name", ''),
            ],
            'applicant_photo'             => data_get($applicant->details, "applicant_photo", []),
            'gender'                      => data_get($applicant->details, "gender", ''),
            'ethnicity'                   => data_get($applicant->details, "ethnicity", ''),
            'ethnicity_other'             => data_get($applicant->details, "ethnicity_other", ''),
            'mother_tongue'               => data_get($applicant->details, "mother_tongue", ''),
            'mother_tongue_other'         => data_get($applicant->details, "mother_tongue_other", ''),
            'disability'                  => data_get($applicant->details, "disability", ''),
            'dob_bs'                      => $applicant->dob_bs,
            'dob_ad'                      => data_get($applicant->details, "dob_ad", ''),
            'citizenship_number'          => $applicant->citizenship_number,
            'citizenship_issued_district' => $applicant->citizenship_issued_district_code,
            'citizenship_issued_date'     => $applicant->citizenship_issued_date_bs,
            'citizenship_files'           => data_get($applicant->details, "citizenship_files", []),
            'permanent_address'           => [
                'district'     => (int) (data_get($applicant, "permanent_address.district")) ?: null,
                'municipality' => (int) (data_get($applicant, "permanent_address.municipality")) ?: null,
                'ward'         => (int) (data_get($applicant, "permanent_address.ward")) ?: null,
                'tole'         => data_get($applicant->permanent_address, "tole", ''),
            ],
            'temporary_address'           => [
                'district'     => (int) (data_get($applicant, "temporary_address.district")) ?: null,
                'municipality' => (int) (data_get($applicant, "temporary_address.municipality")) ?: null,
                'ward'         => (int) (data_get($applicant, "temporary_address.ward")) ?: null,
                'tole'         => data_get($applicant->temporary_address, "tole", ''),
            ],
            'has_current_address'         => (bool) (data_get($applicant->details, "has_current_address")),
            'current_address'             => [
                'district'     => (int) (data_get($applicant, "current_address.district")) ?: null,
                'municipality' => (int) (data_get($applicant, "current_address.municipality")) ?: null,
                'ward'         => (int) (data_get($applicant, "current_address.ward")) ?: null,
                'tole'         => data_get($applicant->current_address, "tole", ''),
            ],
            'current_address_documents'   => data_get($applicant->details, "current_address_documents", []),
            'mobile_number'               => $applicant->mobile_number,
            'mother_name'                 => [
                'first_name'  => data_get($applicant->details, "mother_name.first_name", ''),
                'middle_name' => data_get($applicant->details, "mother_name.middle_name", ''),
                'last_name'   => data_get($applicant->details, "mother_name.last_name", ''),
            ],
            'father_name'                 => [
                'first_name'  => data_get($applicant->details, "father_name.first_name", ''),
                'middle_name' => data_get($applicant->details, "father_name.middle_name", ''),
                'last_name'   => data_get($applicant->details, "father_name.last_name", ''),
            ],
            'grand_father_name'           => [
                'first_name'  => data_get($applicant->details, "grand_father_name.first_name", ''),
                'middle_name' => data_get($applicant->details, "grand_father_name.middle_name", ''),
                'last_name'   => data_get($applicant->details, "grand_father_name.last_name", ''),
            ],
            'spouse_name'                 => [
                'first_name'  => data_get($applicant->details, "spouse_name.first_name", ''),
                'middle_name' => data_get($applicant->details, "spouse_name.middle_name", ''),
                'last_name'   => data_get($applicant->details, "spouse_name.last_name", ''),
            ],
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
                'organization' => data_get($applicant->details, "qualification->experience->organization", ''),
                'period_day'   => data_get($applicant->details, "qualification->experience->period_day", ''),
                'period_month' => data_get($applicant->details, "qualification->experience->period_month", ''),
            ],
        ];
    }
}
