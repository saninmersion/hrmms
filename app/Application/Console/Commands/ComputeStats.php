<?php

namespace App\Application\Console\Commands;

use App\Domain\Applications\Services\StatsService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class ComputeStats
 * @package App\Application\Console\Commands
 */
class ComputeStats extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:compute:stats';

    /**
     * @var string
     */
    protected $description = 'Calculate the stats for dashboard.';

    /**
     * @param StatsService $statsService
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(StatsService $statsService)
    {
        $statsService->overall()->set();
        $statsService->genderBased()->set();
        $statsService->dailyTrend()->set();
        $statsService->districtWise()->set();
        $statsService->overallByDistrict()->set();
        $statsService->genderBasedByDistrict()->set();
        $statsService->municipalityStatsByDistrict()->set();
        $statsService->statsDateTime()->set();

        $this->info('Stats generated.');
    }
}
