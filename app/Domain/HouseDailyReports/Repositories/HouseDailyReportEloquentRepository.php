<?php

namespace App\Domain\HouseDailyReports\Repositories;

use App\Domain\HouseDailyReports\Models\HouseDailyReport;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class HouseDailyReportEloquentRepository
 *
 * @package App\Domain\HouseDailyReports\Repositories
 */
class HouseDailyReportEloquentRepository extends Repository implements HouseDailyReportRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return HouseDailyReport::class;
    }

    /**
     * @param int $createdBy
     *
     * @return $this
     */
    public function byCreatedBy($createdBy): self
    {
        /** @var Builder $builder */
        $builder = $this->getBuilder();

        $this->model = $builder->where('created_by', $createdBy);

        return $this;
    }

    /**
     * @param string $reportDate
     * @param int    $createdBy
     *
     * @return $this
     */
    public function byReportDateAndCreatedBy($reportDate, $createdBy)
    {
        /** @var Builder $builder */
        $builder = $this->getBuilder();

        $this->model = $builder->where(
            function (Builder $query) use ($reportDate, $createdBy) {
                $query->where('report_date', $reportDate);
                $query->where('created_by', $createdBy);
            }
        );

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAdminStats()
    {
        /** @var Builder $builder */
        $builder = $this->getBuilder();

        $builder = $builder->selectRaw(
            "district_code, census_office_id, to_char( report_date, 'yyyy-mm-dd') as date, SUM(total_surveyed) as total, SUM(number_of_houses_in_census) as total_houses_in_census, SUM(number_of_families_in_census) as total_families_in_census"
        );
        $builder = $builder->groupByRaw('1,2,3');
        $builder = $builder->orderByRaw('1,2,3');

        return $builder->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSummaryStats()
    {
        /** @var Builder $builder */
        $builder = $this->getBuilder();

        $sql = "select to_char(hd.report_date, 'yyyy-mm-dd') as date,
        tco.office_name                   as census_office,
        td.title_en                       as district,
        tp.title_en                       as province,
        sum(number_of_houses_in_census)   as cdw,
        sum(total_surveyed)               as dw,
        sum(number_of_families_in_census) as chh
        from tbl_house_daily_reports hd
         left join tbl_census_offices tco on hd.census_office_id = tco.id
         left join tbl_districts td on td.code = hd.district_code
         left join tbl_provinces tp on td.province_code = tp.code
         group by 1,2,3,4
         order by 1 desc,2,3";

        return $builder->fromQuery($sql);
    }

    /**
     * @param int $districtCode
     *
     * @return Collection
     */
    public function getDistrictAdminStats($districtCode)
    {
        /** @var Builder $builder */
        $builder = $this->getBuilder();

        $builder = $builder->selectRaw(
            "district_code, census_office_id, created_by, to_char( report_date, 'yyyy-mm-dd') as date, SUM(total_surveyed) as total, SUM(number_of_houses_in_census) as total_houses_in_census, SUM(number_of_families_in_census) as total_families_in_census"
        );
        $builder = $builder->groupByRaw('1,2,3,4');
        $builder = $builder->orderByRaw('1,2,3,4');
        $builder = $builder->where('district_code', $districtCode);

        return $builder->get();
    }
}
