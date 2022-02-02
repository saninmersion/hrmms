<?php

namespace App\Domain\Applications\Requests;

use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applications\Repositories\ApplicationRepository;
use App\Infrastructure\Constants\StatusTypes;
use App\Infrastructure\Support\Requests\WebFormRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class ApplicationRequest
 * @package App\Domain\Applications\Requests
 */
class ApplicationRequest extends WebFormRequest
{
    /**
     * @var array
     */
    protected $application = [];
    /**
     * @var ApplicationRepository
     */
    protected ApplicationRepository $applicationRepository;
    /**
     * @var ApplicantRepository
     */
    protected ApplicantRepository $applicantRepository;
    /**
     * @var mixed
     */
    protected $applicant;
    /**
     * @var array|mixed
     */
    protected $applicationData;

    /**
     * ApplicationRequest constructor.
     *
     * @param ApplicantRepository   $applicantRepository
     * @param ApplicationRepository $applicationRepository
     */
    public function __construct(ApplicantRepository $applicantRepository, ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
        $this->applicantRepository   = $applicantRepository;
    }

    public function rules(): array
    {
        $rules = [
            'applicant.citizenship_number'               => "required",
            'applicant.citizenship_issued_district_code' => "required",
            'applicant.citizenship_issued_date'          => "required",
            'applicant.dob_bs'                           => "required",
            'applicant.mobile_number'                    => "required",
            'applicant_photo'                            => "nullable|image",
            'application_for'                            => "required",
            'application_district_code'                  => "required",
            'application_municipality_code'              => "required",
            'ward'                                       => "required",
            'details.name_in_devanagari'                 => "required",
            'details.name_in_english'                    => "required",
            'details.gender'                             => "required",
            'details.age'                                => "required",
            'details.family_details.grandfather'         => "required",
            'details.family_details.father'              => "required",
            'details.family_details.mother'              => "required",
            'details.family_details.spouse'              => "required",
            'details.education.qualification'            => "required",
            'details.education.stream'                   => "required",
            'details.education.division'                 => "required",
            'details.education.percentage'               => "required",
            'details.education.major'                    => "required",
            'details.training.institute'                 => "required",
            'details.training.period'                    => "required",
            'details.work_experience.institute'          => "required",
            'details.work_experience.period.year'        => "required",
            'details.work_experience.period.month'       => "required",
            'details.work_experience.period.day'         => "required",
            'permanent_address.district'                 => "required",
            'permanent_address.local_government'         => "required",
            'permanent_address.ward'                     => "required",
            'permanent_address.tole'                     => "required",
            'temporary_address.district'                 => "required",
            'temporary_address.local_government'         => "required",
            'temporary_address.ward'                     => "required",
            'temporary_address.tole'                     => "required",
        ];

        return $rules;
    }

    /**
     * @return $this
     */
    public function setData()
    {
        $this->applicant = [
            'citizenship_number'               => $this->get('applicant')['citizenship_number'],
            'citizenship_issued_district_code' => $this->get('applicant')['citizenship_issued_district_code'],
            'dob_bs'                           => $this->get('applicant')['dob_bs'],
            'mobile_number'                    => $this->get('applicant')['mobile_number'],
        ];

        $this->applicationData = [
            'application_for'               => $this->get('application_for'),
            'application_district_code'     => $this->get('application_district_code'),
            'application_municipality_code' => $this->get('application_municipality_code'),
            'ward'                          => $this->get('ward'),
            'permanent_address'             => $this->get('permanent_address'),
            'temporary_address'             => $this->get('temporary_address'),
            'details'                       => $this->get('details'),
            'status'                        => StatusTypes::APPLICATION_DRAFT,
            'official'                      => $this->get('official'),
        ];

        return $this;
    }

    /**
     * @return mixed
     */
    public function store()
    {
        DB::beginTransaction();

        try {
            $applicant                             = $this->applicantRepository->create($this->applicant);
            $this->applicationData['applicant_id'] = $applicant->id;
            $this->application                     = $this->applicationRepository->create($this->applicationData);

            $applicant->application()->save($this->application);

        } catch (\Exception $exception) {
            DB::rollBack();
        }

        DB::commit();

        return $this->application;
    }
}
