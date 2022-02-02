<?php

namespace App\Domain\HouseDailyReports\Repositories;

use App\Infrastructure\Support\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DailyReportRepository
 *
 * @package App\Domain\HouseDailyReports\Repositories
 */
interface HouseDailyReportRepository extends RepositoryInterface
{
    /**
     * @param int $createdBy
     *
     * @return $this
     */
    public function byCreatedBy($createdBy): self;

    /**
     * @param string $reportDate
     * @param int    $createdBy
     *
     * @return $this
     */
    public function byReportDateAndCreatedBy($reportDate, $createdBy);

    /**
     * @return Collection
     */
    public function getAdminStats();

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSummaryStats();

    /**
     * @param int $districtCode
     *
     * @return Collection
     */
    public function getDistrictAdminStats($districtCode);
}
