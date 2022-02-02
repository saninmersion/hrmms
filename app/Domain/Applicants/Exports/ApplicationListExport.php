<?php

namespace App\Domain\Applicants\Exports;

use App\Domain\Applicants\Models\ApplicationListView;
use App\Domain\Applicants\Repositories\ApplicationListRepository;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Class ApplicationListExport
 * @package App\Domain\Applicants\Exports
 */
class ApplicationListExport
{
    /**
     * @param ApplicationListView $application
     *
     * @return array
     */
    public function mapData(ApplicationListView $application): array
    {
        return [
            'ID'                => $application->id,
            'Submission Number' => $application->submission_number,
            'Created Date'      => $application->created_at->toDateTimeString(),
            'Submitted Date'    => $application->submitted_date_formatted,
            'Applied Role'      => $application->application_for_formatted,
            'Status'            => $application->status_formatted,

            'P1 - District'    => $application->first_priority_district,
            'P1 - Local level' => $application->first_priority_municipality,
            'P1 - Ward'        => $application->first_priority_ward,
            'P2 - District'    => $application->second_priority_district,
            'P2 - Local level' => $application->second_priority_municipality,
            'P2 - Ward'        => $application->second_priority_ward,

            'Name (English)'    => $application->name_in_english_formatted,
            'Name (Devnagari)'  => $application->name_in_nepali_formatted,
            'Gender'            => $application->gender_formatted,
            'Caste'             => $application->ethnicity_formatted,
            'Local Language'    => $application->mother_tongue_formatted,
            'Disability Status' => $application->disability_formatted,

            'Date of Birth (AD)' => $application->dob_ad_formatted,
            'Date of Birth (BS)' => $application->dob_bs,

            'Citizenship Number'           => $application->citizenship_number,
            'Citizenship Issued District'  => $application->citizenship_issued_district,
            'Citizenship Issued Date (BS)' => $application->citizenship_issued_date_bs,

            'Permanent Address - District'    => $application->permanent_address_district,
            'Permanent Address - Local level' => $application->permanent_address_municipality,
            'Permanent Address - Ward'        => $application->permanent_address_ward,
            'Temporary Address - District'    => $application->temporary_address_district,
            'Temporary Address - Local level' => $application->temporary_address_municipality,
            'Temporary Address - Ward'        => $application->temporary_address_ward,

            'Mobile Number' => $application->mobile_number,

            'Mother\'s name'      => $application->mother_name_formatted,
            'Father\'s name'      => $application->father_name_formatted,
            'Grandfather\'s name' => $application->grand_father_name_formatted,
            'Spouse\'s name'      => $application->spouse_name_formatted,

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
        ];
    }

    /**
     * @return LazyCollection
     */
    public function query(): LazyCollection
    {
        $repository = app(ApplicationListRepository::class);

        return $repository->orderBy('created_at', 'asc')->cursor();
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
