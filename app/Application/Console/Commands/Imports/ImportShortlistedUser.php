<?php

namespace App\Application\Console\Commands\Imports;

use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Infrastructure\Support\Exceptions\FileNotFoundException;
use App\Infrastructure\Support\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Class ImportShortlistedUser
 *
 * @package App\Application\Console\Commands\Imports
 */
class ImportShortlistedUser extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:shortlist';

    /**
     * @var string
     */
    protected $description = 'Import shortlisted user applications from excel';

    /**
     * @return void
     */
    public function handle()
    {
        DB::connection()->disableQueryLog();

        try {
            if ( !Storage::disk('imports')->exists('shortlist/shortlisted_users.xlsx') ) {
                throw new FileNotFoundException();
            }

            $this->info("Importing excel data in memory");
            $shortlist = (new FastExcel)->import(storage_path('imports/shortlist/shortlisted_users.xlsx'));
            $this->info("Excel Data saved into memory");

            foreach ($shortlist->chunk(100) as $chunk) {
                foreach ($chunk as $index => $line) {
                    $applicant = Applicant::find($line['id']);

                    if ( $applicant ) {
                        try {
                            ShortListedApplicant::updateOrCreate(
                                [
                                    'applicant_id' => $line['id'],
                                    'role'         => Str::lower($line['role']),
                                ],
                                [
                                    'municipality_code' => $line['code'],
                                    'is_shortlisted'    => true,
                                    'rank'              => $line['rank'],
                                    'score'             => $line['score'] ?? 0,
                                    'hiring_status'     => null,
                                    'metadata'          => [],
                                ]
                            );
                        } catch (\Throwable $exception) {
                            $this->info($exception->getMessage());
                        }

                        $this->info(sprintf('Applicant with id %s was shortlisted for %s.', $line['id'], $line['role']));
                        $this->newLine();

                        continue;
                    }

                    $this->error(sprintf('Applicant with id %s was not found.', $line['id']));
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
