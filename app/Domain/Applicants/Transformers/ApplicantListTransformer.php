<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

/**
 * Class AdminListTransformer
 *
 * @package App\Domain\Applicants\Transformers
 */
class ApplicantListTransformer extends TransformerAbstract
{
    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    public function transform(Applicant $applicant): array
    {
        return [
            'id'                          => $applicant->id,
            'submission_number'           => data_get($applicant->application, 'submission_number', ''),
            'citizenship_number'          => data_get($applicant, "citizenship_number", ''),
            'citizenship_issued_district' => data_get($applicant, "citizenship_issued_district", ''),
            'dob'                         => data_get($applicant, "dob_bs", ''),
            'created_at'                  => Helper::dateResponse($applicant->created_at),
            'updated_at'                  => Helper::dateResponse($applicant->updated_at),
            'submitted_at'                => $this->getSubmittedAtDate($applicant),
            'status'                      => data_get($applicant, "application.status", ''),
            'name'                        => $applicant->name_in_nepali_formatted,
            'name_in_english'             => $applicant->name_in_english_formatted,
            'application_for'             => data_get($applicant, "application.application_for", ''),
            'gender'                      => data_get($applicant->details, 'gender', ''),
            'mobile_number'               => data_get($applicant, "mobile_number", ''),
            'p1_district'                 => data_get($applicant, "application->first_priority_district", ''),
            'p2_district'                 => data_get($applicant, "application->second_priority_district", ''),
            'verification_status'         => data_get($applicant, "verification->verification_status", 'not_verified'),
            'is_offline'                  => data_get($applicant, "application->is_offline", false),
        ];
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
