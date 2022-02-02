<?php


namespace App\Domain\Monitorings\Requests;


use App\Domain\Monitorings\Repositories\MonitoringRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\WebFormRequest;

class MonitoringFormRequest extends WebFormRequest
{
    /**
     * @var MonitoringRepository
     */
    protected MonitoringRepository $monitoringRepository;
    private array $data;

    public function __construct(MonitoringRepository $monitoringRepository)
    {
        $this->monitoringRepository = $monitoringRepository;
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'district'     => trans('application.district'),
            'municipality' => trans('application.local_level'),
            'ward'         => trans('application.ward'),
            'census_area'  => 'गणना क्षेत्र',

            'monitoring_data.monitored_person_name'      => "भेट गरिएको सुपरिवेक्षक/गणक को नाम",
            'monitoring_data.monitored_person_mobile_no' => "सम्पर्क नं.",
            'monitoring_data.family_count'               => "भेट गरिएको परिवार संख्या",

            'monitoring_data.onsite_monitoring.marking'             => "",
            'monitoring_data.onsite_monitoring.prior_information'   => "",
            'monitoring_data.onsite_monitoring.local_advertisement' => "",
            'monitoring_data.onsite_monitoring.missed_count'        => "",
            'monitoring_data.onsite_monitoring.complaints'          => "",
            'monitoring_data.onsite_monitoring.obstacles'           => "",

            'monitoring_data.monitoring_problems'    => "",
            'monitoring_data.monitoring_suggestions' => "",
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        /** @var User $user */
        $user = AuthHelper::currentUser();

        $rules = [
            'district'     => 'required',
            'municipality' => 'required',
            'ward'         => 'required',
            'census_area'  => 'required',

            'monitoring_data.monitored_person_name'      => "required",
            'monitoring_data.monitored_person_mobile_no' => "required",
            'monitoring_data.family_count'               => "required",

            'monitoring_data.onsite_monitoring.marking'             => "required",
            'monitoring_data.onsite_monitoring.prior_information'   => "required",
            'monitoring_data.onsite_monitoring.local_advertisement' => "required",
            'monitoring_data.onsite_monitoring.missed_count'        => "required",
            'monitoring_data.onsite_monitoring.complaints'          => "required",
            'monitoring_data.onsite_monitoring.obstacles'           => "required",

            'monitoring_data.monitoring_problems'    => "required",
            'monitoring_data.monitoring_suggestions' => "required",
        ];

        if ( $user->role === AuthRoles::OPERATOR ) {
            $rules['monitored_by'] = "required";
        }

        return $rules;
    }

    /**
     * @return $this
     */
    public function setOverallForm()
    {
        /** @var User $user */
        $user       = AuthHelper::currentUser();
        $this->data = [
            'monitoring_form'   => ApplicationConstants::MONITORING_FORM_OVERALL,
            'monitoring_date'   => now(),
            'district_code'     => $this->input('district'),
            'municipality_code' => $this->input('municipality'),
            'ward'              => $this->input('ward'),
            'census_area'       => $this->input('census_area'),
            'monitoring_data'   => $this->input('monitoring_data'),
            'monitored_by_id'   => $user->role === AuthRoles::OPERATOR ? $this->input('monitored_by') : $user->id,
            'entered_by_id'     => $user->id,
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function setOverallFormForUpdate()
    {
        $this->data = [
            'district_code'     => $this->input('district'),
            'municipality_code' => $this->input('municipality'),
            'ward'              => $this->input('ward'),
            'census_area'       => $this->input('census_area'),
            'monitoring_data'   => $this->input('monitoring_data'),
        ];

        return $this;
    }

    /**
     * @return mixed
     */
    public function store()
    {
        return $this->monitoringRepository->create($this->data);
    }

    /**
     * @param int $monitoringId
     *
     * @return mixed
     */
    public function update(int $monitoringId)
    {
        return $this->monitoringRepository->update($this->data, $monitoringId);
    }
}
