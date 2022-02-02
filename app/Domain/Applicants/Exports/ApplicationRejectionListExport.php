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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Class ApplicationRejectionListExport
 * @package App\Domain\Applicants\Exports
 */
class ApplicationRejectionListExport
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
            'Rejection Reason'    => $applicationVerification->rejection_reason ?? '',
            'Remark'              => $applicationVerification->remarks ?? '',

            'Name (English)'     => $application->name_in_english_formatted,
            'Name (Devnagari)'   => $application->name_in_nepali_formatted,
            'Gender'             => $application->gender_formatted,
            'Date of Birth (BS)' => $application->dob_bs,

            'Citizenship Number'           => $application->citizenship_number,
            'Citizenship Issued District'  => $application->citizenship_issued_district,
            'Citizenship Issued Date (BS)' => $application->citizenship_issued_date_bs,

            'Degree Document Provided (Supervisor)' => $application->has_degree_for_supervisor_formatted,
            'Major subject (Supervisor)'            => $application->major_subject_supervisor_formatted,
            'Major subject (Supervisor, if others)' => $application->major_subject_others_supervisor,
            'CGPA (Supervisor)'                     => $application->grade_supervisor,
            'Percentage (Supervisor)'               => $application->percentage_supervisor,

            'Degree Document Provided (Enumerator)' => $application->has_degree_for_enumerator_formatted,
            'CGPA (Enumerator)'                     => $application->grade_enumerator,
            'Percentage (Enumerator)'               => $application->percentage_enumerator,

            'Higher education Document Provided'        => $application->has_higher_degree_formatted,
            'Higher education Main subject'             => $application->major_subject_higher_formatted,
            'Higher education Main subject (If others)' => $application->major_subject_others_extra,

            'Training Taken'                     => $application->has_training_formatted,
            'Training Taken (Document Provided)' => $application->has_training_documents_formatted,
            'Training Institution'               => $application->training_institute,
            'Training Days'                      => $application->training_period_in_days,

            'Statistics Experience'                     => $application->has_experience_formatted,
            'Statistics Experience (Document Provided)' => $application->has_experience_documents_formatted,
            'Statistics Experience Organization'        => $application->experience_organization,
            'Statistics Experience (Month)'             => $application->experience_period_month,
            'Statistics Experience (Days)'              => $application->experience_period_day,

            'Link to verification page' => route('admin.verifications.show',['verifier'    => $applicationVerification->verifier_id,'applicantId' => $application->id]),
            'Link to applicant page'    => route('admin.applications.show', $application->id),
            'Verified by'               => $applicationVerification->verifier->name ?? '',
            'Verified on'               => $applicationVerification->updated_at,
        ];
    }

    /**
     * @return LazyCollection
     */
    public function query(): LazyCollection
    {
        $repository = app(ApplicationListRepository::class);

        return $repository->with('verification')->byVerificationStatus(VerificationStatus::RECOMMENDED_FOR_REJECTION)->orderBy('created_at', 'asc')->cursor();
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
