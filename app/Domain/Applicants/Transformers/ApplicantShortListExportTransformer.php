<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\ApplicationListView;
use League\Fractal\TransformerAbstract;

/**
 * Class ApplicantShortListExportTransformer
 * @package App\Domain\Applicants\Transformers
 */
class ApplicantShortListExportTransformer extends TransformerAbstract
{
    /**
     * @param ApplicationListView $applicantListView
     *
     * @return array
     */
    public function transform(ApplicationListView $applicantListView): array
    {
        return [
            'ID'                => $applicantListView->id,
            'Submission Number' => $applicantListView->submission_number,
            'Score'             => $applicantListView->score,
            'Created Date'      => $applicantListView->created_at->toDateTimeString(),
            'Submitted Date'    => $applicantListView->submitted_date_formatted,
            'Applied Role'      => $applicantListView->application_for_formatted,
            'Status'            => $applicantListView->status_formatted,

            'P1 - District'    => $applicantListView->first_priority_district,
            'P1 - Local level' => $applicantListView->first_priority_municipality,
            'P1 - Ward'        => $applicantListView->first_priority_ward,
            'P2 - District'    => $applicantListView->second_priority_district,
            'P2 - Local level' => $applicantListView->second_priority_municipality,
            'P2 - Ward'        => $applicantListView->second_priority_ward,

            'Name (English)'    => $applicantListView->name_in_english_formatted,
            'Name (Devnagari)'  => $applicantListView->name_in_nepali_formatted,
            'Gender'            => $applicantListView->gender_formatted,
            'Caste'             => $applicantListView->ethnicity_formatted,
            'Local Language'    => $applicantListView->mother_tongue_formatted,
            'Disability Status' => $applicantListView->disability_formatted,

            'Date of Birth (AD)' => $applicantListView->dob_ad_formatted,
            'Date of Birth (BS)' => $applicantListView->dob_bs,

            'Citizenship Number'           => $applicantListView->citizenship_number,
            'Citizenship Issued District'  => $applicantListView->citizenship_issued_district,
            'Citizenship Issued Date (BS)' => $applicantListView->citizenship_issued_date_bs,

            'Permanent Address - District'    => $applicantListView->permanent_address_district,
            'Permanent Address - Local level' => $applicantListView->permanent_address_municipality,
            'Permanent Address - Ward'        => $applicantListView->permanent_address_ward,
            'Temporary Address - District'    => $applicantListView->temporary_address_district,
            'Temporary Address - Local level' => $applicantListView->temporary_address_municipality,
            'Temporary Address - Ward'        => $applicantListView->temporary_address_ward,

            'Mobile Number' => $applicantListView->mobile_number,

            'Mother\'s name'      => $applicantListView->mother_name_formatted,
            'Father\'s name'      => $applicantListView->father_name_formatted,
            'Grandfather\'s name' => $applicantListView->grand_father_name_formatted,
            'Spouse\'s name'      => $applicantListView->spouse_name_formatted,

            'Degree Document Provided (Supervisor)' => $applicantListView->has_degree_for_supervisor_formatted,
            'Major subject (Supervisor)'            => $applicantListView->major_subject_supervisor_formatted,
            'Major subject (Supervisor, if others)' => $applicantListView->major_subject_others_supervisor,
            'CGPA (Supervisor)'                     => $applicantListView->grade_supervisor,
            'Percentage (Supervisor)'               => $applicantListView->percentage_supervisor,

            'Degree Document Provided (Enumerator)' => $applicantListView->has_degree_for_enumerator_formatted,
            'CGPA (Enumerator)'                     => $applicantListView->grade_enumerator,
            'Percentage (Enumerator)'               => $applicantListView->percentage_enumerator,

            'Higher education Document Provided'        => $applicantListView->has_higher_degree_formatted,
            'Higher education Main subject'             => $applicantListView->major_subject_higher_formatted,
            'Higher education Main subject (If others)' => $applicantListView->major_subject_others_extra,

            'Training Taken'                     => $applicantListView->has_training_formatted,
            'Training Taken (Document Provided)' => $applicantListView->has_training_documents_formatted,
            'Training Institution'               => $applicantListView->training_institute,
            'Training Days'                      => $applicantListView->training_period_in_days,

            'Statistics Experience'                     => $applicantListView->has_experience_formatted,
            'Statistics Experience (Document Provided)' => $applicantListView->has_experience_documents_formatted,
            'Statistics Experience Organization'        => $applicantListView->experience_organization,
            'Statistics Experience (Month)'             => $applicantListView->experience_period_month,
            'Statistics Experience (Days)'              => $applicantListView->experience_period_day,
        ];
    }
}
