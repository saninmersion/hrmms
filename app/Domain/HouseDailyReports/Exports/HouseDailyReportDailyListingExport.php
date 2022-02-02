<?php


namespace App\Domain\HouseDailyReports\Exports;


use App\Domain\HouseDailyReports\Models\HouseDailyReport;
use App\Domain\HouseDailyReports\Repositories\HouseDailyReportRepository;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Class HouseDailyReportDailyListingExport
 * @package App\Domain\HouseDailyReports\Exports
 */
class HouseDailyReportDailyListingExport
{
    /**
     * @param mixed $houseDailyReport
     *
     * @return array
     */
    public function mapData($houseDailyReport): array
    {
        return [
            'province'               => data_get($houseDailyReport, 'province'),
            'district'               => data_get($houseDailyReport, 'district'),
            'district census office' => data_get($houseDailyReport, 'census_office'),
            'report date'            => data_get($houseDailyReport, 'date'),
            'dw'                     => data_get($houseDailyReport, 'dw'),
            'cdw'                    => data_get($houseDailyReport, 'cdw'),
            'chh'                    => data_get($houseDailyReport, 'chh'),
        ];
    }

    /**
     * @return LazyCollection
     */
    public function query(): LazyCollection
    {
        /** @var HouseDailyReportRepository $repository */
        $repository = app(HouseDailyReportRepository::class);
        $repository = $repository->with(['supervisor', 'district', 'censusOffice']);

        return $repository->getSummaryStats()->lazy();
    }

    /**
     * @return Generator
     */
    public function generator(): Generator
    {
        foreach ( $this->query() as $houseDailyReport ) {
            yield $houseDailyReport;
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
