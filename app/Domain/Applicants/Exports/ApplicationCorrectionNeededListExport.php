<?php

namespace App\Domain\Applicants\Exports;

use App\Domain\Applicants\Models\ApplicantVerification;
use App\Domain\Applicants\Models\ApplicationListView;
use App\Domain\Applicants\Repositories\ApplicationListRepository;
use App\Infrastructure\Constants\VerificationStatus;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Class ApplicationCorrectionNeededListExport
 *
 * @package App\Domain\Applicants\Exports
 */
class ApplicationCorrectionNeededListExport
{
    /**
     * @param ApplicationListView $application
     *
     * @return array
     */
    public function mapData(ApplicationListView $application): array
    {
        /** @var ApplicantVerification $applicationVerification */
        $applicationVerification = $application->verification;

        return [
            'Submission Number' => $application->submission_number,

            'Verification Status' => $applicationVerification->verification_status,
            'Remark'              => data_get($applicationVerification, "remarks", ''),

            'Name (English)'                => $application->name_in_english_formatted,
            'Name (English) (Modified)'     => data_get($applicationVerification, "modified->name_in_english", ''),
            'Name (Devnagari)'              => $application->name_in_nepali_formatted,
            'Name (Devnagari) (Modified)'   => data_get($applicationVerification, "modified->name_in_nepali", ''),
            'Gender'                        => $application->gender_formatted,
            'Gender (Modified)'             => data_get($applicationVerification, "modified->gender", ''),
            'Date of Birth (BS)'            => $application->dob_bs,
            'Date of Birth (BS) (Modified)' => data_get($applicationVerification, "modified->dob_bs", ''),

            'Citizenship Number'                      => $application->citizenship_number,
            'Citizenship Number (Modified)'           => data_get($applicationVerification, "modified->citizenship_number", ''),
            'Citizenship Issued District'             => $application->citizenship_issued_district,
            'Citizenship Issued District (Modified)'  => $applicationVerification->citizenship_issued_district_formatted,
            'Citizenship Issued Date (BS)'            => $application->citizenship_issued_date_bs,
            'Citizenship Issued Date (BS) (Modified)' => data_get($applicationVerification, "modified->citizenship_issued_date_bs", ''),

            'Degree Document Provided (Supervisor)'            => $application->has_degree_for_supervisor_formatted,
            'Degree Document Provided (Supervisor) (Modified)' => data_get($applicationVerification, "modified->has_degree_for_supervisor", ''),
            'Major subject (Supervisor)'                       => $application->major_subject_supervisor_formatted,
            'Major subject (Supervisor) (Modified)'            => data_get($applicationVerification, "modified->major_subject_supervisor", ''),
            'Major subject (Supervisor, if others)'            => $application->major_subject_others_supervisor,
            'Major subject (Supervisor, if others) (Modified)' => data_get($applicationVerification, "modified->major_subject_others_supervisor", ''),
            'CGPA (Supervisor)'                                => $application->grade_supervisor,
            'CGPA (Supervisor) (Modified)'                     => data_get($applicationVerification, "modified->grade_supervisor", ''),
            'Percentage (Supervisor)'                          => $application->percentage_supervisor,
            'Percentage (Supervisor) (Modified)'               => data_get($applicationVerification, "modified->percentage_supervisor", ''),

            'Degree Document Provided (Enumerator)'            => $application->has_degree_for_enumerator_formatted,
            'Degree Document Provided (Enumerator) (Modified)' => data_get($applicationVerification, "modified->has_degree_for_enumerator", ''),
            'CGPA (Enumerator)'                                => $application->grade_enumerator,
            'CGPA (Enumerator) (Modified)'                     => data_get($applicationVerification, "modified->grade_enumerator", ''),
            'Percentage (Enumerator)'                          => $application->percentage_enumerator,
            'Percentage (Enumerator) (Modified)'               => data_get($applicationVerification, "modified->percentage_enumerator", ''),

            'Higher education Document Provided'                   => $application->has_higher_degree_formatted,
            'Higher education Document Provided (Modified)'        => data_get($applicationVerification, "modified->has_higher_degree", ''),
            'Higher education Main subject'                        => $application->major_subject_higher_formatted,
            'Higher education Main subject (Modified)'             => data_get($applicationVerification, "modified->major_subject_higher", ''),
            'Higher education Main subject (If others)'            => $application->major_subject_others_extra,
            'Higher education Main subject (If others) (Modified)' => data_get($applicationVerification, "modified->major_subject_others_extra", ''),

            'Training Taken'                                => $application->has_training_formatted,
            'Training Taken (Modified)'                     => data_get($applicationVerification, "modified->has_training", ''),
            'Training Taken (Document Provided)'            => $application->has_training_documents_formatted,
            'Training Taken (Document Provided) (Modified)' => data_get($applicationVerification, "modified->has_training_documents", ''),
            'Training Institution'                          => $application->training_institute,
            'Training Institution (Modified)'               => data_get($applicationVerification, "modified->training_institute", ''),
            'Training Days'                                 => $application->training_period_in_days,
            'Training Days (Modified)'                      => data_get($applicationVerification, "modified->training_period_in_days", ''),

            'Statistics Experience'                                => $application->has_experience_formatted,
            'Statistics Experience (Modified)'                     => data_get($applicationVerification, "modified->has_experience", ''),
            'Statistics Experience (Document Provided)'            => $application->has_experience_documents_formatted,
            'Statistics Experience (Document Provided) (Modified)' => data_get($applicationVerification, "modified->has_experience_documents", ''),
            'Statistics Experience Organization'                   => $application->experience_organization,
            'Statistics Experience Organization (Modified)'        => data_get($applicationVerification, "modified->experience_organization", ''),
            'Statistics Experience (Month)'                        => $application->experience_period_month,
            'Statistics Experience (Month) (Modified)'             => data_get($applicationVerification, "modified->experience_period_month", ''),
            'Statistics Experience (Days)'                         => $application->experience_period_day,
            'Statistics Experience (Days) (Modified)'              => data_get($applicationVerification, "modified->experience_period_day", ''),

            'Link to verification page' => route('admin.verifications.show', ['verifier' => $applicationVerification->verifier_id, 'applicantId' => $application->id]),
            'Link to applicant page'    => route('admin.applications.show', $application->id),
            'Verified by'               => data_get($applicationVerification, "verifier->name", ''),
            'Verified on'               => $applicationVerification->updated_at,
        ];
    }

    /**
     * @return LazyCollection
     */
    public function query(): LazyCollection
    {
        $repository = app(ApplicationListRepository::class);

        return $repository->with('verification')->byVerificationStatus(VerificationStatus::CORRECTION_NEEDED)->orderBy('created_at', 'asc')->cursor();
    }

    /**
     * @return Generator
     */
    public function generator(): Generator
    {
        foreach ($this->query() as $applicant) {
            yield $applicant;
        }
    }

    /**
     * @param string $fileName
     *
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function export(string $fileName): void
    {
        /** @var Collection $generator */
        $generator = $this->generator();
        $exporter  = new FastExcel($generator);

        $exporter->export($fileName, [$this, 'mapData']);
    }
}
