<?php


namespace App\Domain\Monitorings\Transformers;


use App\Domain\Monitorings\Models\Monitoring;
use App\Infrastructure\Constants\ApplicationConstants;
use League\Fractal\TransformerAbstract;

class MonitoringFormTransformer extends TransformerAbstract
{
    /**
     * @param Monitoring $monitoring
     *
     * @return array
     */
    public function transform(Monitoring $monitoring): array
    {
        $formType = $monitoring->monitoring_form;

        if ( $formType === ApplicationConstants::MONITORING_FORM_OVERALL ) {
            return $this->getOverallMonitoringForm($monitoring);
        }

        if ( $formType === ApplicationConstants::MONITORING_FORM_SUPERVISOR ) {
            return $this->getSupervisorMonitoringForm($monitoring);
        }

        if ( $formType === ApplicationConstants::MONITORING_FORM_ENUMERATOR ) {
            return $this->getEnumeratorMonitoringForm($monitoring);
        }

        return [];
    }

    protected function getOverallMonitoringForm(Monitoring $monitoring): array
    {
        return [
            'id'              => $monitoring->id,
            'monitoring_form' => $monitoring->monitoring_form ?? ApplicationConstants::MONITORING_FORM_OVERALL,
            'monitored_by'    => $monitoring->monitored_by_id ?? '',
            'district'        => $monitoring->district_code ?? '',
            'municipality'    => $monitoring->municipality_code ?? '',
            'ward'            => $monitoring->ward ?? '',
            'census_area'     => $monitoring->census_area ?? '',
            'monitoring_data' => [
                'family_count'               => data_get($monitoring->monitoring_data, "family_count", ''),
                'monitored_person_mobile_no' => data_get($monitoring->monitoring_data, "monitored_person_mobile_no", ""),
                'monitored_person_name'      => data_get($monitoring->monitoring_data, "monitored_person_name", ""),
                'monitoring_problems'        => data_get($monitoring->monitoring_data, "monitoring_problems", ""),
                'monitoring_suggestions'     => data_get($monitoring->monitoring_data, "monitoring_suggestions", ""),
                'onsite_monitoring'          => [
                    'complaints'          => data_get($monitoring->monitoring_data, "onsite_monitoring.complaints", ''),
                    'local_advertisement' => data_get($monitoring->monitoring_data, "onsite_monitoring.local_advertisement", ''),
                    'marking'             => data_get($monitoring->monitoring_data, "onsite_monitoring.marking", ''),
                    'missed_count'        => data_get($monitoring->monitoring_data, "onsite_monitoring.missed_count", ''),
                    'obstacles'           => data_get($monitoring->monitoring_data, "onsite_monitoring.obstacles", ''),
                    'prior_information'   => data_get($monitoring->monitoring_data, "onsite_monitoring.prior_information", ''),
                ],
            ],
        ];
    }

    protected function getSupervisorMonitoringForm(Monitoring $monitoring): array
    {
        return [
            'id'              => $monitoring->id,
            'monitoring_form' => $monitoring->monitoring_form ?? ApplicationConstants::MONITORING_FORM_SUPERVISOR,
            'monitored_by'    => $monitoring->monitored_by_id ?? '',
            'district'        => $monitoring->district_code ?? '',
            'municipality'    => $monitoring->municipality_code ?? '',
            'ward'            => $monitoring->ward ?? '',
            'census_area'     => $monitoring->census_area ?? '',
            'monitoring_data' => [
                'monitored_person_name'      => data_get($monitoring->monitoring_data, "monitored_person_name", ''),
                'monitored_person_mobile_no' => data_get($monitoring->monitoring_data, "monitored_person_mobile_no", ''),
                'politeness_behaviour'       => [
                    'greeting'     => data_get($monitoring->monitoring_data, "politeness_behaviour.greeting", ''),
                    'introduction' => data_get($monitoring->monitoring_data, "politeness_behaviour.introduction", ''),
                    'objective'    => data_get($monitoring->monitoring_data, "politeness_behaviour.objective", ''),
                    'demeanor'     => data_get($monitoring->monitoring_data, "politeness_behaviour.demeanor", ''),
                    'gratitude'    => data_get($monitoring->monitoring_data, "politeness_behaviour.gratitude", ''),
                ],
                'interview_process'          => [
                    'question_similarity' => data_get($monitoring->monitoring_data, "interview_process.question_similarity", ''),
                    'clarification'       => data_get($monitoring->monitoring_data, "interview_process.clarification", ''),
                    'every_section'       => data_get($monitoring->monitoring_data, "interview_process.every_section", ''),
                ],
                'time_management'            => [
                    'long_argument' => data_get($monitoring->monitoring_data, "time_management.long_argument", ''),
                    'interruption'  => data_get($monitoring->monitoring_data, "time_management.interruption", ''),
                    'impatience'    => data_get($monitoring->monitoring_data, "time_management.impatience", ''),
                ],
                'impartiality'               => [
                    'neutrality'        => data_get($monitoring->monitoring_data, "impartiality.neutrality", ''),
                    'reaction'          => data_get($monitoring->monitoring_data, "impartiality.reaction", ''),
                    'personal_opinion'  => data_get($monitoring->monitoring_data, "impartiality.personal_opinion", ''),
                    'answer_indication' => data_get($monitoring->monitoring_data, "impartiality.answer_indication", ''),
                ],
                'onsite_monitoring'          => [
                    'house_monitorings'  => data_get($monitoring->monitoring_data, "onsite_monitoring.house_monitorings", []),
                    'family_monitorings' => data_get($monitoring->monitoring_data, "onsite_monitoring.family_monitorings", []),
                ],
                'monitoring_suggestions'     => data_get($monitoring->monitoring_data, "monitoring_suggestions", ''),
            ],
        ];
    }

    protected function getEnumeratorMonitoringForm(Monitoring $monitoring): array
    {
        return [
            'id'              => $monitoring->id,
            'monitoring_form' => $monitoring->monitoring_form ?? ApplicationConstants::MONITORING_FORM_ENUMERATOR,
            'monitored_by'    => $monitoring->monitored_by_id ?? '',
            'district'        => $monitoring->district_code ?? '',
            'municipality'    => $monitoring->municipality_code ?? '',
            'ward'            => $monitoring->ward ?? '',
            'census_area'     => $monitoring->census_area ?? '',
            'monitoring_data' => [
                'monitored_person_name'      => data_get($monitoring->monitoring_data, "monitored_person_name", ''),
                'monitored_person_mobile_no' => data_get($monitoring->monitoring_data, "monitored_person_mobile_no", ''),
                'politeness_behaviour'       => [
                    'greeting'     => data_get($monitoring->monitoring_data, "politeness_behaviour.greeting", ''),
                    'introduction' => data_get($monitoring->monitoring_data, "politeness_behaviour.introduction", ''),
                    'objective'    => data_get($monitoring->monitoring_data, "politeness_behaviour.objective", ''),
                    'demeanor'     => data_get($monitoring->monitoring_data, "politeness_behaviour.demeanor", ''),
                    'gratitude'    => data_get($monitoring->monitoring_data, "politeness_behaviour.gratitude", ''),
                ],
                'interview_process'          => [
                    'question_similarity' => data_get($monitoring->monitoring_data, "interview_process.question_similarity", ''),
                    'clarification'       => data_get($monitoring->monitoring_data, "interview_process.clarification", ''),
                    'every_section'       => data_get($monitoring->monitoring_data, "interview_process.every_section", ''),
                ],
                'time_management'            => [
                    'long_argument' => data_get($monitoring->monitoring_data, "time_management.long_argument", ''),
                    'interruption'  => data_get($monitoring->monitoring_data, "time_management.interruption", ''),
                    'impatience'    => data_get($monitoring->monitoring_data, "time_management.impatience", ''),
                ],
                'impartiality'               => [
                    'neutrality'        => data_get($monitoring->monitoring_data, "impartiality.neutrality", ''),
                    'reaction'          => data_get($monitoring->monitoring_data, "impartiality.reaction", ''),
                    'personal_opinion'  => data_get($monitoring->monitoring_data, "impartiality.personal_opinion", ''),
                    'answer_indication' => data_get($monitoring->monitoring_data, "impartiality.answer_indication", ''),
                ],
                'onsite_monitoring'          => [
                    "house_sn"                    => data_get($monitoring->monitoring_data, "onsite_monitoring.house_sn", ''),
                    "family_sn"                   => data_get($monitoring->monitoring_data, "onsite_monitoring.family_sn", ''),
                    "head_of_household_name"      => data_get($monitoring->monitoring_data, "onsite_monitoring.head_of_household_name", ''),
                    "head_of_household_age"       => data_get($monitoring->monitoring_data, "onsite_monitoring.head_of_household_age", ''),
                    "head_of_household_gender"    => data_get($monitoring->monitoring_data, "onsite_monitoring.head_of_household_gender", ''),
                    "resident_family_total"       => data_get($monitoring->monitoring_data, "onsite_monitoring.resident_family_total", ''),
                    "resident_family_male"        => data_get($monitoring->monitoring_data, "onsite_monitoring.resident_family_male", ''),
                    "resident_family_female"      => data_get($monitoring->monitoring_data, "onsite_monitoring.resident_family_female", ''),
                    "resident_family_check"       => data_get($monitoring->monitoring_data, "onsite_monitoring.resident_family_check", ''),
                    "absent_family_total"         => data_get($monitoring->monitoring_data, "onsite_monitoring.absent_family_total", ''),
                    "absent_family_male"          => data_get($monitoring->monitoring_data, "onsite_monitoring.absent_family_male", ''),
                    "absent_family_female"        => data_get($monitoring->monitoring_data, "onsite_monitoring.absent_family_female", ''),
                    "absent_family_check"         => data_get($monitoring->monitoring_data, "onsite_monitoring.absent_family_check", ''),
                    "current_house_owner"         => data_get($monitoring->monitoring_data, "onsite_monitoring.current_house_owner", ''),
                    "current_house_code"          => data_get($monitoring->monitoring_data, "onsite_monitoring.current_house_code", ''),
                    "current_house_check"         => data_get($monitoring->monitoring_data, "onsite_monitoring.current_house_check", ''),
                    "current_house_roof"          => data_get($monitoring->monitoring_data, "onsite_monitoring.current_house_roof", ''),
                    "current_house_roof_code"     => data_get($monitoring->monitoring_data, "onsite_monitoring.current_house_roof_code", ''),
                    "current_house_roof_check"    => data_get($monitoring->monitoring_data, "onsite_monitoring.current_house_roof_check", ''),
                    "facility_television_code"    => data_get($monitoring->monitoring_data, "onsite_monitoring.facility_television_code", ''),
                    "facility_television_check"   => data_get($monitoring->monitoring_data, "onsite_monitoring.facility_television_check", ''),
                    "facility_computer_code"      => data_get($monitoring->monitoring_data, "onsite_monitoring.facility_computer_code", ''),
                    "facility_computer_check"     => data_get($monitoring->monitoring_data, "onsite_monitoring.facility_computer_check", ''),
                    "facility_refrigerator_code"  => data_get($monitoring->monitoring_data, "onsite_monitoring.facility_refrigerator_code", ''),
                    "facility_refrigerator_check" => data_get($monitoring->monitoring_data, "onsite_monitoring.facility_refrigerator_check", ''),
                    "family_death"                => data_get($monitoring->monitoring_data, "onsite_monitoring.family_death", ''),
                    "family_death_code"           => data_get($monitoring->monitoring_data, "onsite_monitoring.family_death_code", ''),
                    "family_death_check"          => data_get($monitoring->monitoring_data, "onsite_monitoring.family_death_check", ''),
                    "child_less_than_one_count"   => data_get($monitoring->monitoring_data, "onsite_monitoring.child_less_than_one_count", ''),
                    "child_less_than_one_code"    => data_get($monitoring->monitoring_data, "onsite_monitoring.child_less_than_one_code", ''),
                    "child_less_than_one_check"   => data_get($monitoring->monitoring_data, "onsite_monitoring.child_less_than_one_check", ''),
                    "elderly_more_than_60_count"  => data_get($monitoring->monitoring_data, "onsite_monitoring.elderly_more_than_60_count", ''),
                    "elderly_more_than_60_code"   => data_get($monitoring->monitoring_data, "onsite_monitoring.elderly_more_than_60_code", ''),
                    "elderly_more_than_60_check"  => data_get($monitoring->monitoring_data, "onsite_monitoring.elderly_more_than_60_check", ''),
                    "literate_read_write_count"   => data_get($monitoring->monitoring_data, "onsite_monitoring.literate_read_write_count", ''),
                    "literate_read_write_code"    => data_get($monitoring->monitoring_data, "onsite_monitoring.literate_read_write_code", ''),
                    "literate_read_write_check"   => data_get($monitoring->monitoring_data, "onsite_monitoring.literate_read_write_check", ''),
                    "disabled_count"              => data_get($monitoring->monitoring_data, "onsite_monitoring.disabled_count", ''),
                    "disabled_code"               => data_get($monitoring->monitoring_data, "onsite_monitoring.disabled_code", ''),
                    "disabled_check"              => data_get($monitoring->monitoring_data, "onsite_monitoring.disabled_check", ''),
                ],
                'monitoring_suggestions'     => data_get($monitoring->monitoring_data, "monitoring_suggestions", ''),
            ],
        ];
    }
}
