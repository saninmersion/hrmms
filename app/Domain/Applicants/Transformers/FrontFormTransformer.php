<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\ApplicationType;
use League\Fractal\TransformerAbstract;

/**
 * Class FrontFormTransformer
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
                'application_for' => $applicant->application->application_for ?? ApplicationType::SUPERVISOR,
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
                'first_name'  => $applicant->details->name_in_nepali->first_name ?? '',
                'middle_name' => $applicant->details->name_in_nepali->middle_name ?? '',
                'last_name'   => $applicant->details->name_in_nepali->last_name ?? '',
            ],
            'name_in_english'             => [
                'first_name'  => $applicant->details->name_in_english->first_name ?? '',
                'middle_name' => $applicant->details->name_in_english->middle_name ?? '',
                'last_name'   => $applicant->details->name_in_english->last_name ?? '',
            ],
            'applicant_photo'             => $applicant->details->applicant_photo ?? [],
            'gender'                      => $applicant->details->gender ?? '',
            'ethnicity'                   => $applicant->details->ethnicity ?? '',
            'ethnicity_other'             => $applicant->details->ethnicity_other ?? '',
            'mother_tongue'               => $applicant->details->mother_tongue ?? '',
            'mother_tongue_other'         => $applicant->details->mother_tongue_other ?? '',
            'disability'                  => $applicant->details->disability ?? '',
            'dob_bs'                      => $applicant->dob_bs,
            'dob_ad'                      => $applicant->details->dob_ad ?? '',
            'citizenship_number'          => $applicant->citizenship_number,
            'citizenship_issued_district' => $applicant->citizenship_issued_district_code,
            'citizenship_issued_date'     => $applicant->citizenship_issued_date_bs,
            'citizenship_files'           => $applicant->details->citizenship_files ?? [],
            'permanent_address'           => [
                'district'     => (int) ($applicant->permanent_address->district ?? null) ?: null,
                'municipality' => (int) ($applicant->permanent_address->municipality ?? null) ?: null,
                'ward'         => (int) ($applicant->permanent_address->ward ?? null) ?: null,
                'tole'         => $applicant->permanent_address->tole ?? '',
            ],
            'temporary_address'           => [
                'district'     => (int) ($applicant->temporary_address->district ?? null) ?: null,
                'municipality' => (int) ($applicant->temporary_address->municipality ?? null) ?: null,
                'ward'         => (int) ($applicant->temporary_address->ward ?? null) ?: null,
                'tole'         => $applicant->temporary_address->tole ?? '',
            ],
            'has_current_address'         => (bool) ($applicant->details->has_current_address ?? false),
            'current_address'             => [
                'district'     => (int) ($applicant->current_address->district ?? null) ?: null,
                'municipality' => (int) ($applicant->current_address->municipality ?? null) ?: null,
                'ward'         => (int) ($applicant->current_address->ward ?? null) ?: null,
                'tole'         => $applicant->current_address->tole ?? '',
            ],
            'current_address_documents'   => $applicant->details->current_address_documents ?? [],
            'mobile_number'               => $applicant->mobile_number,
            'mother_name'                 => [
                'first_name'  => $applicant->details->mother_name->first_name ?? '',
                'middle_name' => $applicant->details->mother_name->middle_name ?? '',
                'last_name'   => $applicant->details->mother_name->last_name ?? '',
            ],
            'father_name'                 => [
                'first_name'  => $applicant->details->father_name->first_name ?? '',
                'middle_name' => $applicant->details->father_name->middle_name ?? '',
                'last_name'   => $applicant->details->father_name->last_name ?? '',
            ],
            'grand_father_name'           => [
                'first_name'  => $applicant->details->grand_father_name->first_name ?? '',
                'middle_name' => $applicant->details->grand_father_name->middle_name ?? '',
                'last_name'   => $applicant->details->grand_father_name->last_name ?? '',
            ],
            'spouse_name'                 => [
                'first_name'  => $applicant->details->spouse_name->first_name ?? '',
                'middle_name' => $applicant->details->spouse_name->middle_name ?? '',
                'last_name'   => $applicant->details->spouse_name->last_name ?? '',
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
            'has_education_qualification'    => (bool) ($applicant->details->qualification->has_education_qualification ?? false),
            'files_for_supervisor_education' => $applicant->details->files_for_supervisor_education ?? [],
            'files_for_enumerator_education' => $applicant->details->files_for_enumerator_education ?? [],
            'files_for_extra_education'      => $applicant->details->files_for_extra_education ?? [],
            'education'                      => [
                'supervisor' => [
                    'files'               => [],
                    'grading_system'      => $applicant->details->qualification->education->supervisor->grading_system ?? ApplicationConstants::GRADING_SYSTEM_GRADE,
                    'percentage'          => $applicant->details->qualification->education->supervisor->percentage ?? null,
                    'grade'               => $applicant->details->qualification->education->supervisor->grade ?? null,
                    'major_subject'       => $applicant->details->qualification->education->supervisor->major_subject ?? null,
                    'major_subject_other' => $applicant->details->qualification->education->supervisor->major_subject_other ?? null,
                ],
                'enumerator' => [
                    'files'               => [],
                    'grading_system'      => $applicant->details->qualification->education->enumerator->grading_system ?? ApplicationConstants::GRADING_SYSTEM_GRADE,
                    'percentage'          => $applicant->details->qualification->education->enumerator->percentage ?? null,
                    'grade'               => $applicant->details->qualification->education->enumerator->grade ?? null,
                    'major_subject'       => $applicant->details->qualification->education->enumerator->major_subject ?? null,
                    'major_subject_other' => $applicant->details->qualification->education->enumerator->major_subject_other ?? null,
                ],
                'extra'      => [
                    'major_subject'       => $applicant->details->qualification->education->extra->major_subject ?? null,
                    'major_subject_other' => $applicant->details->qualification->education->extra->major_subject_other ?? null,
                ],
            ],
            'has_training'                   => (bool) ($applicant->details->qualification->has_training ?? false),
            'training_documents'             => $applicant->details->training_documents ?? [],
            'training'                       => [
                'institute' => $applicant->details->qualification->training->institute ?? '',
                'period'    => $applicant->details->qualification->training->period ?? '',
            ],
            'has_experience'                 => (bool) ($applicant->details->qualification->has_experience ?? false),
            'experience_documents'           => $applicant->details->experience_documents ?? [],
            'experience'                     => [
                'organization' => $applicant->details->qualification->experience->organization ?? '',
                'period_day'   => $applicant->details->qualification->experience->period_day ?? '',
                'period_month' => $applicant->details->qualification->experience->period_month ?? '',
            ],
        ];
    }
}
