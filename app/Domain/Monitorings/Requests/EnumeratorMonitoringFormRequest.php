<?php

namespace App\Domain\Monitorings\Requests;

use App\Domain\Monitorings\Repositories\MonitoringRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\WebFormRequest;

/**
 * class EnumeratorMonitoringFormRequest
 */
class EnumeratorMonitoringFormRequest extends WebFormRequest
{
    /**
     * @var MonitoringRepository
     */
    protected MonitoringRepository $monitoringRepository;

    /**
     * @var array
     */
    private array $data;

    /**
     * @param MonitoringRepository $monitoringRepository
     */
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

            'monitoring_data.monitored_person_name'      => "भेट गरिएको गणक को नाम",
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

            'monitoring_data.onsite_monitoring.house_sn'                    => '',
            'monitoring_data.onsite_monitoring.family_sn'                   => '',
            'monitoring_data.onsite_monitoring.head_of_household_name'      => '',
            'monitoring_data.onsite_monitoring.head_of_household_age'       => '',
            'monitoring_data.onsite_monitoring.head_of_household_gender'    => '',
            'monitoring_data.onsite_monitoring.resident_family_total'       => '',
            'monitoring_data.onsite_monitoring.resident_family_male'        => '',
            'monitoring_data.onsite_monitoring.resident_family_female'      => '',
            'monitoring_data.onsite_monitoring.resident_family_check'       => '',
            'monitoring_data.onsite_monitoring.absent_family_total'         => '',
            'monitoring_data.onsite_monitoring.absent_family_male'          => '',
            'monitoring_data.onsite_monitoring.absent_family_female'        => '',
            'monitoring_data.onsite_monitoring.absent_family_check'         => '',
            'monitoring_data.onsite_monitoring.current_house_owner'         => '',
            'monitoring_data.onsite_monitoring.current_house_check'         => '',
            'monitoring_data.onsite_monitoring.current_house_roof'          => '',
            'monitoring_data.onsite_monitoring.current_house_roof_check'    => '',
            'monitoring_data.onsite_monitoring.facility_television_code'    => '',
            'monitoring_data.onsite_monitoring.facility_television_check'   => '',
            'monitoring_data.onsite_monitoring.facility_computer_code'      => '',
            'monitoring_data.onsite_monitoring.facility_computer_check'     => '',
            'monitoring_data.onsite_monitoring.facility_refrigerator_code'  => '',
            'monitoring_data.onsite_monitoring.facility_refrigerator_check' => '',
            'monitoring_data.onsite_monitoring.family_death'                => '',
            'monitoring_data.onsite_monitoring.family_death_check'          => '',
            'monitoring_data.onsite_monitoring.child_less_than_one_count'   => '',
            'monitoring_data.onsite_monitoring.child_less_than_one_check'   => '',
            'monitoring_data.onsite_monitoring.elderly_more_than_60_count'  => '',
            'monitoring_data.onsite_monitoring.elderly_more_than_60_check'  => '',
            'monitoring_data.onsite_monitoring.literate_read_write_count'   => '',
            'monitoring_data.onsite_monitoring.literate_read_write_check'   => '',
            'monitoring_data.onsite_monitoring.disabled_count'              => '',
            'monitoring_data.onsite_monitoring.disabled_check'              => '',
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

            'monitoring_data.onsite_monitoring.house_sn'                    => 'nullable',
            'monitoring_data.onsite_monitoring.family_sn'                   => 'nullable',
            'monitoring_data.onsite_monitoring.head_of_household_name'      => 'nullable',
            'monitoring_data.onsite_monitoring.head_of_household_age'       => 'nullable',
            'monitoring_data.onsite_monitoring.head_of_household_gender'    => 'nullable',
            'monitoring_data.onsite_monitoring.resident_family_total'       => 'nullable',
            'monitoring_data.onsite_monitoring.resident_family_male'        => 'nullable',
            'monitoring_data.onsite_monitoring.resident_family_female'      => 'nullable',
            'monitoring_data.onsite_monitoring.resident_family_check'       => 'nullable',
            'monitoring_data.onsite_monitoring.absent_family_total'         => 'nullable',
            'monitoring_data.onsite_monitoring.absent_family_male'          => 'nullable',
            'monitoring_data.onsite_monitoring.absent_family_female'        => 'nullable',
            'monitoring_data.onsite_monitoring.absent_family_check'         => 'nullable',
            'monitoring_data.onsite_monitoring.current_house_owner'         => 'nullable',
            'monitoring_data.onsite_monitoring.current_house_check'         => 'nullable',
            'monitoring_data.onsite_monitoring.current_house_roof'          => 'nullable',
            'monitoring_data.onsite_monitoring.current_house_roof_check'    => 'nullable',
            'monitoring_data.onsite_monitoring.facility_television_code'    => 'nullable',
            'monitoring_data.onsite_monitoring.facility_television_check'   => 'nullable',
            'monitoring_data.onsite_monitoring.facility_computer_code'      => 'nullable',
            'monitoring_data.onsite_monitoring.facility_computer_check'     => 'nullable',
            'monitoring_data.onsite_monitoring.facility_refrigerator_code'  => 'nullable',
            'monitoring_data.onsite_monitoring.facility_refrigerator_check' => 'nullable',
            'monitoring_data.onsite_monitoring.family_death'                => 'nullable',
            'monitoring_data.onsite_monitoring.family_death_check'          => 'nullable',
            'monitoring_data.onsite_monitoring.child_less_than_one_count'   => 'nullable',
            'monitoring_data.onsite_monitoring.child_less_than_one_check'   => 'nullable',
            'monitoring_data.onsite_monitoring.elderly_more_than_60_count'  => 'nullable',
            'monitoring_data.onsite_monitoring.elderly_more_than_60_check'  => 'nullable',
            'monitoring_data.onsite_monitoring.literate_read_write_count'   => 'nullable',
            'monitoring_data.onsite_monitoring.literate_read_write_check'   => 'nullable',
            'monitoring_data.onsite_monitoring.disabled_count'              => 'nullable',
            'monitoring_data.onsite_monitoring.disabled_check'              => 'nullable',

        ];

        if ( $user->role === AuthRoles::OPERATOR ) {
            $rules['monitored_by'] = "required";
        }

        return $rules;
    }

    /**
     * @return $this
     */
    public function setEnumeratorForm()
    {
        /** @var User $user */
        $user = AuthHelper::currentUser();

        $this->data = [
            'monitoring_form'   => ApplicationConstants::MONITORING_FORM_ENUMERATOR,
            'monitoring_date'   => now(),
            'district_code'     => $this->input('district'),
            'municipality_code' => $this->input('municipality'),
            'ward'              => $this->input('ward'),
            'census_area'       => $this->input('census_area'),
            'monitoring_data'   => $this->prepareMonitoringData($this->input('monitoring_data')),
            'monitored_by_id'   => $user->role === AuthRoles::OPERATOR ? $this->input('monitored_by') : $user->id,
            'entered_by_id'     => $user->id,
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function setEnumeratorFormForUpdate(): self
    {
        $this->data = [
            'district_code'     => $this->input('district'),
            'municipality_code' => $this->input('municipality'),
            'ward'              => $this->input('ward'),
            'census_area'       => $this->input('census_area'),
            'monitoring_data'   => $this->prepareMonitoringData($this->input('monitoring_data')),
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

    /**
     * @param array $monitoringData
     *
     * @return array
     */
    private function prepareMonitoringData($monitoringData)
    {
        return [
            "monitored_person_name"      => strval(data_get($monitoringData, 'monitored_person_name', '')),
            "monitored_person_mobile_no" => strval(data_get($monitoringData, 'monitored_person_mobile_no', '')),
            "politeness_behaviour"       => [
                "demeanor"     => strval(data_get($monitoringData, 'politeness_behaviour.demeanor', '')),
                "gratitude"    => strval(data_get($monitoringData, 'politeness_behaviour.gratitude', '')),
                "greeting"     => strval(data_get($monitoringData, 'politeness_behaviour.greeting', '')),
                "introduction" => strval(data_get($monitoringData, 'politeness_behaviour.introduction', '')),
                "objective"    => strval(data_get($monitoringData, 'politeness_behaviour.objective', '')),
            ],
            "interview_process"          => [
                "clarification"       => strval(data_get($monitoringData, 'interview_process.clarification', '')),
                "every_section"       => strval(data_get($monitoringData, 'interview_process.every_section', '')),
                "question_similarity" => strval(data_get($monitoringData, 'interview_process.question_similarity', '')),
            ],
            "time_management"            => [
                "impatience"    => strval(data_get($monitoringData, 'time_management.impatience', '')),
                "interruption"  => strval(data_get($monitoringData, 'time_management.interruption', '')),
                "long_argument" => strval(data_get($monitoringData, 'time_management.long_argument', '')),
            ],
            "impartiality"               => [
                "answer_indication" => strval(data_get($monitoringData, 'impartiality.answer_indication', '')),
                "neutrality"        => strval(data_get($monitoringData, 'impartiality.neutrality', '')),
                "personal_opinion"  => strval(data_get($monitoringData, 'impartiality.personal_opinion', '')),
                "reaction"          => strval(data_get($monitoringData, 'impartiality.reaction', '')),
            ],
            "onsite_monitoring"          => [
                "house_sn"                    => strval(data_get($monitoringData, 'onsite_monitoring.house_sn', '')),
                "family_sn"                   => strval(data_get($monitoringData, 'onsite_monitoring.family_sn', '')),
                "head_of_household_name"      => strval(data_get($monitoringData, 'onsite_monitoring.head_of_household_name', '')),
                "head_of_household_age"       => strval(data_get($monitoringData, 'onsite_monitoring.head_of_household_age', '')),
                "head_of_household_gender"    => strval(data_get($monitoringData, 'onsite_monitoring.head_of_household_gender', '')),
                "resident_family_total"       => strval(data_get($monitoringData, 'onsite_monitoring.resident_family_total', '')),
                "resident_family_male"        => strval(data_get($monitoringData, 'onsite_monitoring.resident_family_male', '')),
                "resident_family_female"      => strval(data_get($monitoringData, 'onsite_monitoring.resident_family_female', '')),
                "resident_family_check"       => strval(data_get($monitoringData, 'onsite_monitoring.resident_family_check', '')),
                "absent_family_total"         => strval(data_get($monitoringData, 'onsite_monitoring.absent_family_total', '')),
                "absent_family_male"          => strval(data_get($monitoringData, 'onsite_monitoring.absent_family_male', '')),
                "absent_family_female"        => strval(data_get($monitoringData, 'onsite_monitoring.absent_family_female', '')),
                "absent_family_check"         => strval(data_get($monitoringData, 'onsite_monitoring.absent_family_check', '')),
                "current_house_owner"         => strval(data_get($monitoringData, 'onsite_monitoring.current_house_owner', '')),
                "current_house_check"         => strval(data_get($monitoringData, 'onsite_monitoring.current_house_check', '')),
                "current_house_roof"          => strval(data_get($monitoringData, 'onsite_monitoring.current_house_roof', '')),
                "current_house_roof_check"    => strval(data_get($monitoringData, 'onsite_monitoring.current_house_roof_check', '')),
                "facility_television_code"    => strval(data_get($monitoringData, 'onsite_monitoring.facility_television_code', '')),
                "facility_television_check"   => strval(data_get($monitoringData, 'onsite_monitoring.facility_television_check', '')),
                "facility_computer_code"      => strval(data_get($monitoringData, 'onsite_monitoring.facility_computer_code', '')),
                "facility_computer_check"     => strval(data_get($monitoringData, 'onsite_monitoring.facility_computer_check', '')),
                "facility_refrigerator_code"  => strval(data_get($monitoringData, 'onsite_monitoring.facility_refrigerator_code', '')),
                "facility_refrigerator_check" => strval(data_get($monitoringData, 'onsite_monitoring.facility_refrigerator_check', '')),
                "family_death"                => strval(data_get($monitoringData, 'onsite_monitoring.family_death', '')),
                "family_death_check"          => strval(data_get($monitoringData, 'onsite_monitoring.family_death_check', '')),
                "child_less_than_one_count"   => strval(data_get($monitoringData, 'onsite_monitoring.child_less_than_one_count', '')),
                "child_less_than_one_check"   => strval(data_get($monitoringData, 'onsite_monitoring.child_less_than_one_check', '')),
                "elderly_more_than_60_count"  => strval(data_get($monitoringData, 'onsite_monitoring.elderly_more_than_60_count', '')),
                "elderly_more_than_60_check"  => strval(data_get($monitoringData, 'onsite_monitoring.elderly_more_than_60_check', '')),
                "literate_read_write_count"   => strval(data_get($monitoringData, 'onsite_monitoring.literate_read_write_count', '')),
                "literate_read_write_check"   => strval(data_get($monitoringData, 'onsite_monitoring.literate_read_write_check', '')),
                "disabled_count"              => strval(data_get($monitoringData, 'onsite_monitoring.disabled_count', '')),
                "disabled_check"              => strval(data_get($monitoringData, 'onsite_monitoring.disabled_check', '')),
            ],
            "monitoring_suggestions"     => strval(data_get($monitoringData, 'monitoring_suggestions', '')),
        ];
    }
}
