<?php

namespace App\Domain\Monitorings\Transformers;

use App\Domain\Monitorings\Models\Monitoring;
use App\Infrastructure\Constants\ApplicationConstants;
use League\Fractal\TransformerAbstract;

/**
 * Class OverallMonitoringFormTransformer
 *
 * @package App\Domain\Monitorings\Transformers
 */
class OverallMonitoringFormTransformer extends TransformerAbstract
{
    /**
     * @param Monitoring $monitoring
     *
     * @return array
     */
    public function transform(Monitoring $monitoring)
    {
        return [
            'id'              => $monitoring->id,
            'monitoring_form' => $monitoring->monitoring_form ?? ApplicationConstants::MONITORING_FORM_OVERALL,
            'monitored_by'    => $monitoring->monitored_by_id,
            'district'        => $monitoring->district_code,
            'municipality'    => $monitoring->municipality_code,
            'ward'            => $monitoring->ward,
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
}
