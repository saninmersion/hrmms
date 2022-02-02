<?php


namespace App\Domain\Monitorings\Transformers;


use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\Monitorings\Models\Monitoring;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

class MonitoringListTransformer extends TransformerAbstract
{
    public function transform(Monitoring $monitoring): array
    {
        return [
            'id'                         => $monitoring->id,
            'form'                       => $monitoring->monitoring_form,
            'district'                   => $monitoring->monitoredDistrict ? $this->getLocationFormat($monitoring->monitoredDistrict) : '',
            'municipality'               => $monitoring->monitoredMunicipality ? $this->getLocationFormat($monitoring->monitoredMunicipality) : '',
            'monitoring_date'            => Helper::dateResponse($monitoring->monitoring_date),
            'monitored_person_name'      => data_get($monitoring, 'monitoring_data.monitored_person_name', ''),
            'monitored_person_mobile_no' => data_get($monitoring, 'monitoring_data.monitored_person_mobile_no', ''),
            'monitored_by'               => data_get($monitoring->monitoredBy, 'name'),
        ];
    }

    /**
     * @param District|Municipality|null $location
     *
     * @return string
     */
    protected function getLocationFormat($location): string
    {
        if ( !$location ) {
            return '';
        }
        $locale = app()->getLocale();

        return $locale === 'en' ? $location->title_en : $location->title_ne;
    }
}
