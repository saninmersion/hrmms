<?php

namespace App\Application\Console\Commands;

use App\Domain\Applicants\Actions\ExportApplicationList;
use Illuminate\Console\Command;
use League\Flysystem\FileNotFoundException;

/**
 * Class ApplicationsExport
 * @package App\Application\Console\Commands
 */
class ApplicationsExport extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:applications:export';

    /**
     * @var string
     */
    protected $description = 'Export applications list to excel';

    /**
     * @param ExportApplicationList $exportApplicationList
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle(ExportApplicationList $exportApplicationList)
    {
        $exportApplicationList->export();

        $this->info('Application list exported.');
    }
}
