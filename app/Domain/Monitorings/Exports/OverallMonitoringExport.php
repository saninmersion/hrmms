<?php

namespace App\Domain\Monitorings\Exports;

use App\Domain\Monitorings\Models\Monitoring;
use App\Domain\Monitorings\Repositories\MonitoringRepository;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\MonitoringFormConstants;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Class OverallMonitoringExport
 * @package App\Domain\Monitorings\Exports
 */
class OverallMonitoringExport
{
    /**
     * @param Monitoring $monitoring
     *
     * @return array
     */
    public function mapData(Monitoring $monitoring): array
    {
        return [
            'Monitored By'                            => optional($monitoring->monitoredBy)->name,
            'Monitoring Date'                         => $monitoring->monitoring_date,
            'District'                                => optional($monitoring->monitoredDistrict)->title_ne,
            'Municipality'                            => optional($monitoring->monitoredMunicipality)->title_ne,
            'Name of Supervisor / Enumerator Visited' => optional($monitoring->monitoring_data)->monitored_person_name,
            'Contact no'                              => optional($monitoring->monitoring_data)->monitored_person_mobile_no,
            'Number of families visited'              => optional($monitoring->monitoring_data)->family_count,

            'परिवार बसोबास गरेको घरमा मार्करले जनगणना घर तथा घरपरिवार क्रं. सं. लेखेको नलेखेको' => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->marking)),
            'घरपरिवारलाई जनगणनाको वारेमा गणना पुर्व जानकारी भए नभएको'                           => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->prior_information)),
            'अनुगमन गरिएका क्षेत्रमा जनगणना सम्बन्धी स्थानीय रुपमा प्रचार प्रसार भए नभएको'      => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->local_advertisement)),
            'गणना गरिसकिएको गाउँ/टोल/बस्तीमा कुनै घर परिवार गणना गर्न छुट भए नभएको'             => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->missed_count)),
            'जनगणनाको स्थलगत गणना कार्य प्रती उत्तरदाताहरुको कुनै गुनासो भए नभएको'              => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->complaints)),
            'जनगणनाको स्थलगत कार्यमा बाधा व्यवधान भए नभएको'                                     => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->obstacles)),

            'Other problems observed' => optional($monitoring->monitoring_data)->monitoring_problems,
            'Suggestions'             => optional($monitoring->monitoring_data)->monitoring_suggestions,
        ];
    }

    /**
     * @return LazyCollection
     */
    public function query(): LazyCollection
    {
        $repository = app(MonitoringRepository::class);
        $repository = $repository->with(['monitoredBy', 'monitoredDistrict', 'monitoredMunicipality'])->byMonitoringType(MonitoringFormConstants::FORM_OVERALL);

        if ( data_get(auth()->user(), 'role') === AuthRoles::DISTRICT_STAFFS ) {
            $repository = $repository->byDistrict(data_get(auth()->user(), 'district_code'));
        }

        return $repository->cursor();
    }

    /**
     * @return Generator
     */
    public function generator(): Generator
    {
        foreach ($this->query() as $monitoring) {
            yield $monitoring;
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
