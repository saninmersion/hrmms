<?php

namespace App\Domain\DailyReports\Repositories;

use App\Infrastructure\Support\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DailyReportRepository
 * @package App\Domain\DailyReports\Repositories
 */
interface DailyReportRepository extends RepositoryInterface
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
     * @param int $enumeratorId
     *
     * @return $this
     */
    public function byEnumerator(int $enumeratorId): self;

    /**
     * @return Collection
     */
    public function getAdminStats();

    /**
     * @param int $districtCode
     *
     * @return Collection
     */
    public function getDistrictAdminStats($districtCode);
}
