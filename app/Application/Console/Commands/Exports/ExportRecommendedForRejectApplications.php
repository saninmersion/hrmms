<?php

namespace App\Application\Console\Commands\Exports;

use App\Domain\Applicants\Actions\ExportApplicationList;
use App\Domain\Applicants\Actions\ExportApplicationRejectionList;
use App\Domain\Applicants\Exports\ApplicationRejectionListExport;
use Illuminate\Console\Command;
use League\Flysystem\FileNotFoundException;

/**
 * Class ExportRecommendedForRejectApplications
 * @package App\Application\Console\Commands\Exports
 */
class ExportRecommendedForRejectApplications extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:export:applications:recommended_for_rejection';

    /**
     * @var string
     */
    protected $description = 'Export recommended for rejection applications list to excel';

    /**
     * @param ExportApplicationRejectionList $exportApplications
     * @return void
     * @throws FileNotFoundException
     */
    public function handle(ExportApplicationRejectionList $exportApplications)
    {
        $exportApplications->export();

        $this->info('Recommended for rejection Application list exported.');
    }
}
