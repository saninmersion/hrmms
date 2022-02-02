<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\ApplicationListView;
use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Infrastructure\Constants\ApplicationType;
use League\Fractal\TransformerAbstract;

/**
 * Class ShortListedApplicantListTransformer
 * @package App\Domain\Applicants\Transformers
 */
class ShortListedApplicantListTransformer extends TransformerAbstract
{
    /**
     * @param ApplicationListView $applicant
     *
     * @return array
     */
    public function transform(ApplicationListView $applicant): array
    {
        $applicants = $applicant->shortlist;

        /** @var ShortListedApplicant|null $shortListEnumerator */
        $shortListEnumerator = $applicants->where('role', ApplicationType::ENUMERATOR)->first();
        /** @var ShortListedApplicant|null $shortListSupervisor */
        $shortListSupervisor = $applicants->where('role', ApplicationType::SUPERVISOR)->first();

        return [
            'id'                          => $applicant->id,
            'name_in_nepali'              => $applicant->name_in_nepali_formatted,
            'name_in_english'             => $applicant->name_in_english_formatted,
            'citizenship_number'          => $applicant->citizenship_number,
            'citizenship_issued_district' => $applicant->citizenship_issued_district,
            'dob_bs'                      => $applicant->dob_bs,
            'dob_ad'                      => $applicant->dob_ad_formatted,
            'gender'                      => $applicant->gender_formatted,

            // @todo Age to be calculated properly based upon last date of deadline
            'age'                         => $applicant->dob_ad ? $applicant->dob_ad->diffInYears() : null,

            'rank'            => $applicant->shortlist->first()->rank ?? null,
            'priority'        => $this->getPriority($applicant),
            'verified'        => false,
            'application_for' => $applicant->application_for,
            'is_shortlisted'  => [
                'enumerator' => $shortListEnumerator && $shortListEnumerator->is_shortlisted ?? false,
                'supervisor' => $shortListSupervisor && $shortListSupervisor->is_shortlisted ?? false,
            ],
            'hiring_status'   => [
                'enumerator' => $shortListEnumerator->hiring_status ?? '',
                'supervisor' => $shortListSupervisor->hiring_status ?? '',
            ],
        ];
    }

    /**
     * @param ApplicationListView $applicant
     *
     * @return int
     */
    protected function getPriority(ApplicationListView $applicant): int
    {
        $municipality = request()->input('municipality');
        if ( $applicant->first_priority_municipality_code === (int) $municipality ) {
            return 1;
        }

        return 2;
    }
}
