<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\ApplicationType;
use League\Fractal\TransformerAbstract;

/**
 * Class ApplicationVerificationTransformer
 * @package App\Domain\Applicants\Transformers
 */
class ApplicationVerificationTransformer extends TransformerAbstract
{
    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    public function transform(Applicant $applicant): array
    {
        $supervisorGrading = $applicant->details->qualification->education->supervisor->grading_system ?? ApplicationConstants::GRADING_SYSTEM_GRADE;
        $enumeratorGrading = $applicant->details->qualification->education->enumerator->grading_system ?? ApplicationConstants::GRADING_SYSTEM_GRADE;

        $enumeratorGP = $enumeratorGrading === ApplicationConstants::GRADING_SYSTEM_PERCENTAGE ? $applicant->details->qualification->education->enumerator->percentage ?? null : $applicant->details->qualification->education->enumerator->grade ?? null;

        $supervisorGP = $supervisorGrading === ApplicationConstants::GRADING_SYSTEM_PERCENTAGE ? $applicant->details->qualification->education->supervisor->percentage ?? null : $applicant->details->qualification->education->supervisor->grade ?? null;

        return [
            'id'                                       => $applicant->id,
            'application_for'                          => $applicant->application->application_for ?? ApplicationType::SUPERVISOR,
            'citizenship_number'                       => $applicant->citizenship_number,
            'citizenship_issued_district'              => $applicant->citizenship_issued_district_code,
            'citizenship_issued_date'                  => $applicant->citizenship_issued_date_bs,
            'dob_bs'                                   => $applicant->dob_bs,
            'name_in_nepali'                           => $applicant->name_in_nepali_formatted,
            'name_in_english'                          => $applicant->name_in_english_formatted,
            'gender'                                   => $applicant->details->gender ?? '',
            'has_education_qualification'              => (bool) ($applicant->details->qualification->has_education_qualification ?? false),
            'education_supervisor_grading_system'      => $supervisorGrading,
            'education_supervisor_grade_percentage'    => $supervisorGP,
            'education_supervisor_percentage'          => $applicant->details->qualification->education->supervisor->percentage ?? null,
            'education_supervisor_grade'               => $applicant->details->qualification->education->supervisor->grade ?? null,
            'education_supervisor_major_subject'       => $applicant->details->qualification->education->supervisor->major_subject ?? 'others',
            'education_supervisor_major_subject_other' => $applicant->details->qualification->education->supervisor->major_subject_other ?? null,
            'education_enumerator_grading_system'      => $enumeratorGrading,
            'education_enumerator_grade_percentage'    => $enumeratorGP,
            'education_enumerator_percentage'          => $applicant->details->qualification->education->enumerator->percentage ?? null,
            'education_enumerator_grade'               => $applicant->details->qualification->education->enumerator->grade ?? null,
            'education_enumerator_major_subject'       => $applicant->details->qualification->education->enumerator->major_subject ?? 'others',
            'education_enumerator_major_subject_other' => $applicant->details->qualification->education->enumerator->major_subject_other ?? null,
            'has_training'                             => (bool) ($applicant->details->qualification->has_training ?? false),
            'training_institute'                       => $applicant->details->qualification->training->institute ?? '',
            'training_period'                          => $applicant->details->qualification->training->period ?? '',
            'has_experience'                           => (bool) ($applicant->details->qualification->has_experience ?? false),
            'experience_organization'                  => $applicant->details->qualification->experience->organization ?? '',
            'experience_period_day'                    => $applicant->details->qualification->experience->period_day ?? '',
            'experience_period_month'                  => $applicant->details->qualification->experience->period_month ?? '',
            'documents'                                => $this->getDocuments($applicant),
        ];
    }

    /**
     * @param Applicant $applicant
     *
     * @return array
     */
    protected function getDocuments(Applicant $applicant): array
    {
        return [
            'citizenship_files'              => $applicant->details->citizenship_files ?? [],
            'files_for_supervisor_education' => $applicant->details->files_for_supervisor_education ?? [],
            'files_for_enumerator_education' => $applicant->details->files_for_enumerator_education ?? [],
            'experience_documents'           => $applicant->details->experience_documents ?? [],
            'training_documents'             => $applicant->details->training_documents ?? [],
        ];
    }
}
