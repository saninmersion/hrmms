<?php

namespace App\Application\Console\Commands;

use App\Domain\LastUpdated\Actions\UpdateApplicationList;
use Illuminate\Console\Command;

/**
 * Class ComputeDashboardStats
 * @package App\Application\Console\Commands
 */
class RefreshApplicationListView extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:refresh:application-list';

    /**
     * @var string
     */
    protected $description = 'Refresh application list materialized view.';

    /**
     * @param UpdateApplicationList $updateApplicationList
     *
     * @return void
     */
    public function handle(UpdateApplicationList $updateApplicationList)
    {
        $updateApplicationList->refreshViewAndUpdate();

        $this->info('Application List Refreshed.');
    }
}
