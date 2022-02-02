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
 * Class SupervisorMonitoringExport
 * @package App\Domain\Monitorings\Exports
 */
class SupervisorMonitoringExport
{
    /**
     * @param Monitoring $monitoring
     *
     * @return array
     */
    public function mapData(Monitoring $monitoring): array
    {
        return [
            'Monitored By'                                                                                                              => optional($monitoring->monitoredBy)->name,
            'Monitoring Date'                                                                                                           => $monitoring->monitoring_date,
            'District'                                                                                                                  => optional($monitoring->monitoredDistrict)->title_ne,
            'Municipality'                                                                                                              => optional($monitoring->monitoredMunicipality)->title_ne,
            'Name of Supervisor / Enumerator Visited'                                                                                   => optional($monitoring->monitoring_data)->monitored_person_name,
            'Contact no'                                                                                                                => optional($monitoring->monitoring_data)->monitored_person_mobile_no,
            'Number of families visited'                                                                                                => optional($monitoring->monitoring_data)->family_count,

            // सुपरिवेक्षकको शिष्टता र व्यवहार
            'अन्तर्वार्ता शुरु हुनु भन्दा पहिले सुपरिवेक्षकले उत्तरदातालाई अभिवादन गरे'                                                 => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->greeting)),
            'सुपरिवेक्षकलेआफ्नो परिचय दिएर आफु राष्ट्रिय जनगणनाको तथ्या संकलनका लागि आवश्यक विवरण संकलन गर्न आएको हो भन्ने कुरा बताए ?' => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->introduction)),
            'सुपरिवेक्षकले जनगणनाको उद्देश्यको बारेमा जानकारी गराए ?'                                                                   => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->objective)),
            'अन्तर्वार्ताको समयमा उत्तरदातासँग सुपरिवेक्षकको व्यवहार मिजासिलो, नम्र र धैर्यशील रह्यो ?'                                 => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->demeanor)),
            'अन्तर्वार्ता सकिएपछी सुपरिवेक्षकले उत्तरदाता र अन्य सम्बिन्धत सबैलाई धन्यवाद ज्ञापन गरे ?'                                 => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->gratitude)),

            // अन्तर्वार्ता प्रक्रिया
            'सुपरिवेक्षकले प्रश्नावलिमा लेखिएको प्रश्न जस्ताको तस्तै पढेर उत्तरदातालाई सोधेका थिए ?'                                    => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->interview_process)->question_similarity)),
            'उत्तरदाताले कुनै प्रश्न नबुझेमा सुपरिवेक्षकले त्यस्ता प्रश्नलाई प्रश्नको आशय नबिग्रने गरी थप स्पष्ट पारेका थिए ?'          => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->interview_process)->clarification)),
            'सुपरिवेक्षकले प्रश्नावली को प्रत्येक खण्डका प्रत्येक प्रश्न सोधेर परिवारमूलीले दिएको उत्तर लेखेका थिए ?'                   => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->interview_process)->every_section)),

            // समय व्यवस्थापन
            'सुपरिवेक्षकले उत्तरदातासँग कुनै प्रश्नमा लामो बहस गर्ने गरेका थिए ?'                                                       => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->time_management)->long_argument)),
            'उत्तरदाताबाट असान्दर्भिक वा जटिल उत्तर पाउने बित्तिकै सुपरिवेक्षकले बीचमा बोलेर उत्तरदातालाई रोकेका थिए ?'                 => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->time_management)->interruption)),
            'उत्तरदातालाई छिटो छिटो उत्तर दिन लगाउने वा सुपरिवेक्षकले अन्तर्वार्ता लिंदा हतार गरेका थिए ?'                              => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->time_management)->impatience)),

            // निष्पक्षता
            'अन्तर्वार्ता लिँदा सुपरिवेक्षकले प्रश्न र उत्तर दुबैमा तटस्थ व्यवहार देखाएका थिए ?'                                        => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->impartiality)->neutrality)),
            'उत्तर्दाताबाट प्राप्त कुनै उत्तर सुनेर सुपरिवेक्षक आश्चर्य वा उत्तर मन नपराएको जस्तो हाउभाउ देखाएका थिए ?'                 => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->impartiality)->reaction)),
            'सुपरिवेक्षकले कुनै प्रश्नको सन्दर्भमा आफ्नो निजी विचार उत्तरदातालाई सुनाएका थिए ?'                                         => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->impartiality)->personal_opinion)),

            // सपुरिवेक्षकले सूचीकरण गरेको आधारमा घर सम्बन्धी विवरणको स्थलगत जाँच

            'Suggestions' => optional($monitoring->monitoring_data)->monitoring_suggestions,
        ];
    }

    /**
     * @return LazyCollection
     */
    public function query(): LazyCollection
    {
        $repository = app(MonitoringRepository::class);
        $repository = $repository->with(['monitoredBy', 'monitoredDistrict', 'monitoredMunicipality'])->byMonitoringType(MonitoringFormConstants::FORM_SUPERVISOR);
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
