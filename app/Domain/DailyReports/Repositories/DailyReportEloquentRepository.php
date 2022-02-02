<?php

namespace App\Domain\DailyReports\Repositories;

use App\Domain\DailyReports\Models\DailyReport;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DailyReportEloquentRepository
 * @package App\Domain\DailyReports\Repositories
 */
class DailyReportEloquentRepository extends Repository implements DailyReportRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return DailyReport::class;
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
     * @param int $enumeratorId
     *
     * @return $this
     */
    public function byEnumerator(int $enumeratorId): self
    {
        /** @var Builder $builder */
        $builder = $this->getBuilder();

        $this->model = $builder->where('enumerator_id', $enumeratorId);

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

        $builder = $builder->selectRaw("district_code, census_office_id, to_char( report_date, 'yyyy-mm-dd') as date, SUM(total_surveyed) as total");
        $builder = $builder->groupByRaw('1,2,3');
        $builder = $builder->orderByRaw('1,2,3');

        return $builder->get();
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

        $builder = $builder->selectRaw("district_code, census_office_id, created_by,  enumerator_id, to_char( report_date, 'yyyy-mm-dd') as date, SUM(total_surveyed) as total");
        $builder = $builder->groupByRaw('1,2,3,4,5');
        $builder = $builder->orderByRaw('1,2,3,4,5');
        $builder = $builder->where('district_code', $districtCode);

        return $builder->get();
    }
}
