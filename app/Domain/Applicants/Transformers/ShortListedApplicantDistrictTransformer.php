<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\ApplicationListView;
use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

/**
 * Class ShortListedApplicantDistrictTransformer
 * @package App\Domain\Applicants\Transformers
 */
class ShortListedApplicantDistrictTransformer extends TransformerAbstract
{
    /**
     * @param ApplicationListView $applicantView
     *
     * @return array
     */
    public function transform(ApplicationListView $applicantView): array
    {
        $shortlist = $applicantView->shortlist()->get();

        /** @var ShortListedApplicant|null $shortListEnumerator */
        $shortListEnumerator = $shortlist->where('role', ApplicationType::ENUMERATOR)->first();
        /** @var ShortListedApplicant|null $shortListSupervisor */
        $shortListSupervisor = $shortlist->where('role', ApplicationType::SUPERVISOR)->first();

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
            'application_for' => $applicantView->application_for ?? '',
            'location'        => [
                'district'     => $applicantView->shortlist->first()->municipality->district->title_ne,
                'municipality' => $applicantView->shortlist->first()->municipality->title_ne,
            ],
            'personal'        => $this->getPersonal($applicantView),
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
            'name_in_nepali'    => $applicantView->name_in_nepali_formatted,
            'name_in_english'   => $applicantView->name_in_english_formatted,
            'gender'            => $applicantView->gender ?? '',
            'dob_bs'            => $applicantView->dob_bs,
            'dob_ad'            => $applicantView->dob_ad_formatted ?? '',
            'citizenship'       => $this->getCitizenship($applicantView),
            'mobile_number'     => $applicantView->mobile_number ?? '',
            'grand_father_name' => $applicantView->grand_father_name_formatted,
            'father_name'       => $applicantView->father_name_formatted,
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
    protected function getCitizenship(ApplicationListView $applicantView): array
    {
        return [
            'number'          => $applicantView->citizenship_number ?? '',
            'issued_district' => $applicantView->citizenship_issued_district ?? '',
            'issued_date'     => $applicantView->citizenship_issued_date_bs ?? '',
        ];
    }
}
