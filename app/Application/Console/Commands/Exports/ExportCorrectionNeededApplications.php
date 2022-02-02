<?php

namespace App\Application\Console\Commands\Exports;

use App\Domain\Applicants\Actions\ExportApplicationCorrectionNeededList;
use Illuminate\Console\Command;
use League\Flysystem\FileNotFoundException;

/**
 * Class ExportCorrectionNeededApplications
 * @package App\Application\Console\Commands\Exports
 */
class ExportCorrectionNeededApplications extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:export:applications:correction_needed';

    /**
     * @var string
     */
    protected $description = 'Export correction needed applications list to excel';

    /**
     * @param ExportApplicationCorrectionNeededList $exportApplications
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle(ExportApplicationCorrectionNeededList $exportApplications)
    {
        $exportApplications->export();

        $this->info('Correction needed Application list exported.');
    }
}
