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
 * Class EnumeratorMonitoringExport
 * @package App\Domain\Monitorings\Exports
 */
class EnumeratorMonitoringExport
{
    /**
     * @param Monitoring $monitoring
     *
     * @return array
     */
    public function mapData(Monitoring $monitoring): array
    {
        return [
            'Monitored By'                                                                                             => optional($monitoring->monitoredBy)->name,
            'Monitoring Date'                                                                                          => $monitoring->monitoring_date,
            'District'                                                                                                 => optional($monitoring->monitoredDistrict)->title_ne,
            'Municipality'                                                                                             => optional($monitoring->monitoredMunicipality)->title_ne,
            'Name of Supervisor / Enumerator Visited'                                                                  => optional($monitoring->monitoring_data)->monitored_person_name,
            'Contact no'                                                                                               => optional($monitoring->monitoring_data)->monitored_person_mobile_no,
            'Number of families visited'                                                                               => optional($monitoring->monitoring_data)->family_count,

            // सगणकको शिष्टता र व्यवहार
            'अन्तर्वार्ता शुरु हुनु भन्दा पहिले गणकले उत्तरदातालाई अभिवादन गरे ?'                                      => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->greeting)),
            'गणकले आफ्नो परिचय दिएर आफु राष्ट्रिय जनगणनाको तथ्या संकलनका'                                              => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->introduction)),
            'गणकले जनगणनाको उद्देश्यको बारेमा जानकारी गराए ?'                                                          => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->objective)),
            'अन्तर्वार्ताको समयमा उत्तरदातासँग गणकको व्यवहार मिजासिलो, नम्र र धैर्यशील रह्यो ?'                        => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->demeanor)),
            'अन्तर्वार्ता सकिएपछी गणकले उत्तरदाता र अन्य सम्बिन्धत सबैलाई धन्यवाद ज्ञापन गरे ?'                        => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->politeness_behaviour)->gratitude)),

            // अन्तर्वार्ता प्रक्रिया
            'गणकले प्रश्नावलिमा लेखिएको प्रश्न जस्ताको तस्तै पढेर उत्तरदातालाई सोधेका थिए ?'                           => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->interview_process)->question_similarity)),
            'उत्तरदाताले कुनै प्रश्न नबुझेमा गणकले त्यस्ता प्रश्नलाई प्रश्नको आशय नबिग्रने गरी थप स्पष्ट पारेका थिए ?' => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->interview_process)->clarification)),
            'गणकले प्रश्नावली को प्रत्येक खण्डका प्रत्येक प्रश्न सोधेर परिवारमूलीले दिएको उत्तर लेखेका थिए ?'          => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->interview_process)->every_section)),

            // समय व्यवस्थापन
            'गणकले उत्तरदातासँग कुनै प्रश्नमा लामो बहस गर्ने गरेका थिए ?'                                              => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->time_management)->long_argument)),
            'उत्तरदाताबाट असान्दर्भिक वा जटिल उत्तर पाउने बित्तिकै गणकले बीचमा बोलेर उत्तरदातालाई रोकेका थिए ?'        => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->time_management)->interruption)),
            'उत्तरदातालाई छिटो छिटो उत्तर दिन लगाउने वा गणकले अन्तर्वार्ता लिंदा हतार गरेका थिए ?'                     => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->time_management)->impatience)),

            // निष्पक्षता
            'अन्तर्वार्ता लिँदा गणकले प्रश्न र उत्तर दुबैमा तटस्थ व्यवहार देखाएका थिए ?'                               => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->impartiality)->neutrality)),
            'उत्तर्दाताबाट प्राप्त कुनै उत्तर सुनेर गणक आश्चर्य वा उत्तर मन नपराएको जस्तो हाउभाउ देखाएका थिए ?'        => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->impartiality)->reaction)),
            'गणकले कुनै प्रश्नको सन्दर्भमा आफ्नो निजी विचार उत्तरदातालाई सुनाएका थिए ?'                                => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->impartiality)->personal_opinion)),
            'गणकले प्रश्न सोध्दा उत्तर पनि आफैले सुझाएको वा संकेत गरेका थिए ?'                                         => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->impartiality)->answer_indication)),

            // मुख्य प्रश्नावली सम्बन्धी विवरणको स्थलगत जाचँ
            'जनगणना घर क्र.सं.'                                                                                        => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->house_sn)),
            'जनगणना परिवार क्र.सं.'                                                                                    => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->family_sn)),
            'परिवारमूलीको नाम'                                                                                         => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->head_of_household_name)),
            'परिवारमूलीको उमेर'                                                                                        => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->head_of_household_age)),
            'परिवारमूलीको लिङ्ग'                                                                                       => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->head_of_household_gender)),

            'अक्सर वसोबास गर्ने परिवारका संख्या'        => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->resident_family_check)),
            'अनुपस्थित (विदेशमा रहेका) परिवारका संख्या' => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->absent_family_check)),

            'तपाईंको परिवारले प्रयोग गरेको घरको स्वामित्व कस्तो हो ?'                          => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->absent_family_check)),
            'तपाईंको परिवारले प्रयोग गरेको घरको छाना के ले बनेकाे छ ?'                         => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->current_house_roof_check)),
            'तपाईंको परिवारमा निम्न घरायसी सुविधा तथा साधनहरू के के छन् ? (टेलिभिजन)'          => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->facility_television_check)),
            'तपाईंको परिवारमा निम्न घरायसी सुविधा तथा साधनहरू के के छन् ? (कम्प्युटर/ल्यापटप)' => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->facility_computer_check)),
            'तपाईंको परिवारमा निम्न घरायसी सुविधा तथा साधनहरू के के छन् ? (रेफ्रिजरेटर)'       => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->facility_refrigerator_check)),

            'गत १२ महिनामा तपाईको परिवारमा कसैको मृत्यु भएको थियो ?' => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->family_death_check)),

            'यस परिवारमा १ बर्ष उमेर नपुगेका बालबालिकाको संख्या कति छ ?'               => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->child_less_than_one_check)),
            'यस परिवारमा ६० बर्ष वा सो भन्दा बढी उमेर माथिका व्यक्तिको संख्या कति छ ?' => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->elderly_more_than_60_check)),
            'यस परिवारमा पढ्न र लेख्न दुवै जान्नेको संख्या कति छ ?'                    => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->literate_read_write_check)),
            'यस परिवारमा अपाङ्गता भएका व्यक्तिको संख्या कति छ?'                        => __(sprintf("%s.%s", "admin-monitoring.form-options", optional($monitoring->monitoring_data->onsite_monitoring)->disabled_check)),

            'Suggestions' => optional($monitoring->monitoring_data)->monitoring_suggestions,
        ];
    }

    /**
     * @return LazyCollection
     */
    public function query(): LazyCollection
    {
        $repository = app(MonitoringRepository::class);
        $repository = $repository->with(['monitoredBy', 'monitoredDistrict', 'monitoredMunicipality'])->byMonitoringType(MonitoringFormConstants::FORM_ENUMERATOR);
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
