<?php

namespace App\Application\Console\Commands\Imports;

use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Support\Exceptions\FileNotFoundException;
use App\Infrastructure\Support\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Class ImportSupervisorShortlist
 * @package App\Application\Console\Commands\Imports
 */
class ImportSupervisorShortlist extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:shortlist:supervisor';

    /**
     * @var string
     */
    protected $description = 'Import shortlisted supervisor applications list from excel';

    /**
     * @return void
     */
    public function handle()
    {
        DB::connection()->disableQueryLog();

        try {
            if ( !Storage::disk('imports')->exists('shortlist/supervisor.xlsx') ) {
                throw new FileNotFoundException();
            }

            $this->info("Importing excel data in memory");
            $shortlist = (new FastExcel)->import(storage_path('imports/shortlist/supervisor.xlsx'));
            $this->info("Excel Data saved into memory");

            foreach ($shortlist->chunk(100) as $chunk) {
                foreach ($chunk as $index => $line) {
                    $applicant = Applicant::find($line['id']);

                    if ( $applicant ) {
                        ShortListedApplicant::firstOrCreate(
                            [
                                'applicant_id' => $line['id'],
                                'role'         => ApplicationType::SUPERVISOR,
                            ],
                            [
                                'municipality_code' => $line['code'],
                                'is_shortlisted'    => true,
                                'rank'              => $line['new_rank'],
                                'score'             => $line['score'] ?? 0,
                                'hiring_status'     => null,
                                'metadata'          => [],
                            ]
                        );

                        $this->info(sprintf('%s with id %s was shortlisted.', $line['name_english'], $line['id']));
                        $this->newLine();

                        continue;
                    }

                    $this->error(sprintf('%s with id %s was not found.', $line['name_english'], $line['id']));
                    $this->newLine();
                }
            }

        } catch (FileNotFoundException $exception) {
            $this->error($exception->getMessage());
        } catch (\Exception $exception) {
            Helper::logException($exception);
        }

        DB::connection()->enableQueryLog();
    }
}
