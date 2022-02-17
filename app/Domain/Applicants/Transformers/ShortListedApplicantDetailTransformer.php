<?php


namespace App\Domain\Applicants\Transformers;


use App\Domain\Applicants\Models\ApplicationListView;
use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

class ShortListedApplicantDetailTransformer extends TransformerAbstract
{
    /**
     * @param ApplicationListView $applicantView
     *
     * @return array
     */
    public function transform(ApplicationListView $applicantView): array
    {
        $applicants = $applicantView->shortlist()->get();

        /** @var ShortListedApplicant|null $shortListEnumerator */
        $shortListEnumerator = $applicants->where('role', ApplicationType::ENUMERATOR)->first();
        /** @var ShortListedApplicant|null $shortListSupervisor */
        $shortListSupervisor = $applicants->where('role', ApplicationType::SUPERVISOR)->first();

        return [
            'id' => $applicantView->id,

            'is_shortlisted' => [
                'enumerator' => $shortListEnumerator && $shortListEnumerator->is_shortlisted ?? false,
                'supervisor' => $shortListSupervisor && $shortListSupervisor->is_shortlisted ?? false,
            ],
            'hiring'         => [
                'enumerator' => [
                    'status'       => data_get($shortListEnumerator, 'hiring_status', ''),
                    'comments'     => data_get($shortListEnumerator, 'metadata.comments', ''),
                    'district'     => $shortListEnumerator && $shortListEnumerator->municipality ? $shortListEnumerator->municipality->district_code : '',
                    'municipality' => $shortListEnumerator && $shortListEnumerator->municipality ? $shortListEnumerator->municipality->code : '',
                ],
                'supervisor' => [
                    'status'       => data_get($shortListSupervisor, 'hiring_status', ''),
                    'comments'     => data_get($shortListSupervisor, 'metadata.comments', ''),
                    'district'     => $shortListSupervisor && $shortListSupervisor->municipality ? $shortListSupervisor->municipality->district_code : '',
                    'municipality' => $shortListSupervisor && $shortListSupervisor->municipality ? $shortListSupervisor->municipality->code : '',
                ],
            ],

            'is_submitted'      => $applicantView->is_submitted ?? false,
            'submission_number' => $applicantView->submission_number ?? '',
            'status'            => $applicantView->status ?? '',

            'dates'           => $this->getDates($applicantView),
            'application_for' => data_get($applicantView, "application_for", ''),
            'location_first'  => [
                'district'     => $applicantView->first_priority_district,
                'municipality' => $applicantView->first_priority_municipality,
            ],
            'personal'        => $this->getPersonal($applicantView),
            'qualification'   => $this->getQualification($applicantView),
        ];
    }

    /**
     * @param ApplicationListView $applicantView
     *
     * @return array
     */
    protected function getPersonal(ApplicationListView $applicantView): array
    {
        return [
            'name_in_nepali'          => $applicantView->name_in_nepali_formatted,
            'name_in_english'         => $applicantView->name_in_english_formatted,
            'applicant_photo'         => [],
            'gender'                  => data_get($applicantView, "gender", ''),
            'ethnicity'               => data_get($applicantView, "ethnicity", ''),
            'ethnicity_formatted'     => data_get($applicantView, "ethnicity_formatted", ''),
            'mother_tongue'           => data_get($applicantView, "mother_tongue", ''),
            'mother_tongue_formatted' => data_get($applicantView, "mother_tongue_formatted", ''),
            'disability'              => data_get($applicantView, "disability_formatted"),
            'dob_bs'                  => data_get($applicantView, "dob_bs"),
            'dob_ad'                  => data_get($applicantView, "dob_ad_formatted", ''),
            'citizenship'             => $this->getCitizenship($applicantView),
            'addresses'               => $this->getAddresses($applicantView),
            'mobile_number'           => data_get($applicantView, "mobile_number", ''),
            'father_name'             => $applicantView->father_name_formatted,
            'grand_father_name'       => $applicantView->grand_father_name_formatted,
            'mother_name'             => $applicantView->mother_name_formatted,
            'spouse_name'             => $applicantView->spouse_name_formatted,
        ];
    }

    /**
     * @param ApplicationListView $applicantView
     *
     * @return array
     */
    protected function getQualification(ApplicationListView $applicantView): array
    {
        return [
            'has_education_qualification'    => (bool) data_get($applicantView, 'has_education_qualification', false),
            'files_for_supervisor_education' => [],
            'files_for_enumerator_education' => [],
            'files_for_extra_education'      => [],
            'education'                      => [
                'supervisor' => [
                    'files'               => [],
                    'grading_system'      => data_get($applicantView, 'grading_system', ApplicationConstants::GRADING_SYSTEM_GRADE),
                    'percentage'          => data_get($applicantView, "percentage_supervisor", null),
                    'grade'               => data_get($applicantView, "grade_supervisor", null),
                    'major_subject'       => data_get($applicantView, "major_subject_supervisor", null),
                    'major_subject_other' => data_get($applicantView, "major_subject_others_supervisor", null),
                ],
                'enumerator' => [
                    'files'               => [],
                    'grading_system'      => data_get($applicantView, 'grading_system', ApplicationConstants::GRADING_SYSTEM_GRADE),
                    'percentage'          => data_get($applicantView, "percentage_enumerator", null),
                    'grade'               => data_get($applicantView, "grade_enumerator", null),
                    'major_subject'       => data_get($applicantView, 'major_subject_enumerator', null),
                    'major_subject_other' => data_get($applicantView, 'major_subject_other_enumerator', null),
                ],
                'extra'      => [
                    'major_subject'       => data_get($applicantView, "major_subject_extra", null),
                    'major_subject_other' => data_get($applicantView, "major_subject_others_extra", null),
                ],
            ],
            'has_training'                   => (bool) ($applicantView->has_training ?? false),
            'training_documents'             => [],
            'training'                       => [
                'institute' => data_get($applicantView, "training_institute", ''),
                'period'    => data_get($applicantView, "training_period_in_days"),
                'files'     => [],
            ],
            'has_experience'                 => (bool) ($applicantView->has_experience),
            'experience_documents'           => [],
            'experience'                     => [
                'organization' => data_get($applicantView, "experience_organization", ''),
                'period_day'   => data_get($applicantView, "experience_period_day", ''),
                'period_month' => data_get($applicantView, "experience_period_month", ''),
                'files'        => [],
            ],
        ];
    }


    /**
     * @param ApplicationListView $applicantView
     *
     * @return array
     */
    protected function getDates(ApplicationListView $applicantView): array
    {
        return [
            'created_at'   => Helper::dateResponse($applicantView->created_at),
            'submitted_at' => $applicantView->application_date ? Helper::dateResponse($applicantView->application_date) : null,
        ];
    }

    /**
     * @param ApplicationListView $applicantView
     *
     * @return array
     */
    protected function getAddresses(ApplicationListView $applicantView): array
    {
        return [
            'permanent_address'         => [
                'district'     => $applicantView->permanent_address_district,
                'municipality' => $applicantView->permanent_address_municipality,
                'ward'         => $applicantView->permanent_address_ward ?? '',
            ],
            'temporary_address'         => [
                'district'     => $applicantView->temporary_address_district,
                'municipality' => $applicantView->temporary_address_municipality,
                'ward'         => $applicantView->temporary_address_ward ?? '',
            ],
            'current_address_documents' => [],
        ];
    }

    /**
     * @param ApplicationListView $applicantView
     *
     * @return array
     */
    protected function getCitizenship(ApplicationListView $applicantView): array
    {
        return [
            'number'          => $applicantView->citizenship_number ?? '',
            'issued_district' => $applicantView->citizenship_issued_district ?? '',
            'issued_date'     => $applicantView->citizenship_issued_date_bs ?? '',
            'files'           => [],
        ];
    }
}
