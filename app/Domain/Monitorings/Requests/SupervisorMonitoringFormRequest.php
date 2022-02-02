<?php


namespace App\Domain\Monitorings\Requests;


use App\Domain\Monitorings\Repositories\MonitoringRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\WebFormRequest;

class SupervisorMonitoringFormRequest extends WebFormRequest
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

            'monitoring_data.monitored_person_name'      => "भेट गरिएको सुपरिवेक्षकको नाम",
            'monitoring_data.monitored_person_mobile_no' => "सम्पर्क नं.",

            'monitoring_data.politeness_behaviour.greeting'     => "",
            'monitoring_data.politeness_behaviour.introduction' => "",
            'monitoring_data.politeness_behaviour.objective'    => "",
            'monitoring_data.politeness_behaviour.demeanor'     => "",
            'monitoring_data.politeness_behaviour.gratitude'    => "",

            'monitoring_data.interview_process.question_similarity' => "",
            'monitoring_data.interview_process.clarification'       => "",
            'monitoring_data.interview_process.every_section'       => "",

            'monitoring_data.time_management.long_argument' => "",
            'monitoring_data.time_management.interruption'  => "",
            'monitoring_data.time_management.impatience'    => "",

            'monitoring_data.impartiality.neutrality'        => "",
            'monitoring_data.impartiality.reaction'          => "",
            'monitoring_data.impartiality.personal_opinion'  => "",
            'monitoring_data.impartiality.answer_indication' => "",

            'monitoring_data.onsite_monitoring.house_monitorings.*.house_sn'            => "",
            'monitoring_data.onsite_monitoring.house_monitorings.*.floor_count'         => "",
            'monitoring_data.onsite_monitoring.house_monitorings.*.floor_count_check'   => "",
            'monitoring_data.onsite_monitoring.house_monitorings.*.house_purpose'       => "",
            'monitoring_data.onsite_monitoring.house_monitorings.*.house_purpose_check' => "",
            'monitoring_data.onsite_monitoring.house_monitorings.*.family_count'        => "",
            'monitoring_data.onsite_monitoring.house_monitorings.*.family_count_check'  => "",

            'monitoring_data.onsite_monitoring.family_monitorings.*.family_sn'                   => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.house_head_name'             => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.house_head_name_check'       => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.male_count'                  => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.male_count_check'            => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.female_count'                => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.female_count_check'          => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.agriculture_land'            => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.agriculture_land_check'      => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.professional_training'       => "",
            'monitoring_data.onsite_monitoring.family_monitorings.*.professional_training_check' => "",
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

            'monitoring_data.politeness_behaviour.greeting'     => "required",
            'monitoring_data.politeness_behaviour.introduction' => "required",
            'monitoring_data.politeness_behaviour.objective'    => "required",
            'monitoring_data.politeness_behaviour.demeanor'     => "required",
            'monitoring_data.politeness_behaviour.gratitude'    => "required",

            'monitoring_data.interview_process.question_similarity' => "required",
            'monitoring_data.interview_process.clarification'       => "required",
            'monitoring_data.interview_process.every_section'       => "required",

            'monitoring_data.time_management.long_argument' => "required",
            'monitoring_data.time_management.interruption'  => "required",
            'monitoring_data.time_management.impatience'    => "required",

            'monitoring_data.impartiality.neutrality'        => "required",
            'monitoring_data.impartiality.reaction'          => "required",
            'monitoring_data.impartiality.personal_opinion'  => "required",
            'monitoring_data.impartiality.answer_indication' => "required",

            'monitoring_data.onsite_monitoring.house_monitorings.*.house_sn'            => "required",
            'monitoring_data.onsite_monitoring.house_monitorings.*.floor_count'         => "required",
            'monitoring_data.onsite_monitoring.house_monitorings.*.floor_count_check'   => "required",
            'monitoring_data.onsite_monitoring.house_monitorings.*.house_purpose'       => "required",
            'monitoring_data.onsite_monitoring.house_monitorings.*.house_purpose_check' => "required",
            'monitoring_data.onsite_monitoring.house_monitorings.*.family_count'        => "required",
            'monitoring_data.onsite_monitoring.house_monitorings.*.family_count_check'  => "required",

            'monitoring_data.onsite_monitoring.family_monitorings.*.family_sn'                   => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.house_head_name'             => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.house_head_name_check'       => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.male_count'                  => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.male_count_check'            => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.female_count'                => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.female_count_check'          => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.agriculture_land'            => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.agriculture_land_check'      => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.professional_training'       => "required",
            'monitoring_data.onsite_monitoring.family_monitorings.*.professional_training_check' => "required",

        ];

        if ( $user->role === AuthRoles::OPERATOR ) {
            $rules['monitored_by'] = "required";
        }

        return $rules;
    }

    /**
     * @return $this
     */
    public function setSupervisorForm(): self
    {
        /** @var User $user */
        $user = AuthHelper::currentUser();

        $this->data = [
            'monitoring_form'   => ApplicationConstants::MONITORING_FORM_SUPERVISOR,
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
    public function setSupervisorFormForUpdate(): self
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
