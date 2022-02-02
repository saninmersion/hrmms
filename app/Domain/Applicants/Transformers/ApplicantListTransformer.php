<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

/**
 * Class AdminListTransformer
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
            'citizenship_number'          => $applicant->citizenship_number ?? '',
            'citizenship_issued_district' => $applicant->citizenship_issued_district ?? '',
            'dob'                         => $applicant->dob_bs ?? '',
            'created_at'                  => Helper::dateResponse($applicant->created_at),
            'updated_at'                  => Helper::dateResponse($applicant->updated_at),
            'submitted_at'                => $this->getSubmittedAtDate($applicant),
            'status'                      => $applicant->application ? $applicant->application->status : '',
            'name'                        => $applicant->name_in_nepali_formatted,
            'name_in_english'             => $applicant->name_in_english_formatted,
            'application_for'             => $applicant->application ? $applicant->application->application_for : '',
            'gender'                      => data_get($applicant->details, 'gender', ''),
            'mobile_number'               => $applicant->mobile_number ?? '',
            'p1_district'                 => $applicant->application->first_priority_district ?? '',
            'p2_district'                 => $applicant->application->second_priority_district ?? '',
            'verification_status'         => $applicant->verification->verification_status ?? 'not_verified',
            'is_offline'                  => $applicant->application->is_offline ?? false,
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
