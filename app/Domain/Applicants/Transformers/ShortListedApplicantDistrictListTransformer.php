<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\ShortListedApplicant;
use League\Fractal\TransformerAbstract;

/**
 * Class ShortListedApplicantDistrictListTransformer
 * @package App\Domain\Applicants\Transformers
 */
class ShortListedApplicantDistrictListTransformer extends TransformerAbstract
{
    /**
     * @param ShortListedApplicant $shortListedApplicant
     *
     * @return array
     */
    public function transform(ShortListedApplicant $shortListedApplicant): array
    {
        return [
            'id'                          => $shortListedApplicant->applicationView->id,
            'name_in_nepali'              => $shortListedApplicant->applicationView->name_in_nepali_formatted,
            'name_in_english'             => $shortListedApplicant->applicationView->name_in_english_formatted,
            'citizenship_number'          => $shortListedApplicant->applicationView->citizenship_number,
            'citizenship_issued_district' => $shortListedApplicant->applicationView->citizenship_issued_district,
            'dob_bs'                      => $shortListedApplicant->applicationView->dob_bs,
            'dob_ad'                      => $shortListedApplicant->applicationView->dob_ad_formatted,
            'gender'                      => $shortListedApplicant->applicationView->gender_formatted,

            // @todo Age to be calculated properly based upon last date of deadline
            'age'                         => $shortListedApplicant->applicationView->dob_ad ? $shortListedApplicant->applicationView->dob_ad->diffInYears() : null,

            'district'        => $this->getPriority($shortListedApplicant),
            'application_for' => $shortListedApplicant->applicationView->application_for,
            'is_shortlisted'  => $shortListedApplicant->is_shortlisted,
            'rank'            => $shortListedApplicant->rank,
            'hiring_status'   => $shortListedApplicant->hiring_status ?? '',
        ];
    }

    /**
     * @param ShortListedApplicant $applicant
     *
     * @return int
     */
    protected function getPriority(ShortListedApplicant $applicant): int
    {
        $municipality = $applicant->municipality_code;
        if ( $applicant->applicationView->first_priority_municipality_code === (int) $municipality ) {
            return 1;
        }

        return 2;
    }
}
