<?php

namespace App\Application\Console\Commands\Imports;

use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Models\VerifierAssignment;
use App\Infrastructure\Support\Exceptions\FileNotFoundException;
use App\Infrastructure\Support\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Class AssignDraftVerifiers
 * @package App\Application\Console\Commands\Imports
 */
class AssignDraftVerifiers extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:assign:verifiers';

    /**
     * @var string
     */
    protected $description = 'Assign draft enumerator applications from excel';

    /**
     * @return void
     */
    public function handle()
    {
        DB::connection()->disableQueryLog();

        try {
            if ( !Storage::disk('imports')->exists('verification/draft-verifications.xlsx') ) {
                throw new FileNotFoundException();
            }

            $this->info("Importing excel data in memory");
            $applicants = (new FastExcel)->import(storage_path('imports/verification/draft-verifications.xlsx'));
            $this->info("Excel Data saved into memory");

            foreach ($applicants->chunk(100) as $chunk) {
                foreach ($chunk as $index => $line) {
                    $applicant = Applicant::find($line['applicant_id']);

                    if ( $applicant ) {
                        VerifierAssignment::create(
                            [
                                'applicant_id' => $line['applicant_id'],
                                'verifier_id'  => $line['verifier_id'],
                            ]
                        );
                    }
                }
            }

        } catch (FileNotFoundException $exception) {
            $this->error($exception->getMessage());
        } catch (\Exception $exception) {
            Helper::logException($exception);
            $this->error($exception->getMessage());
        }

        DB::connection()->enableQueryLog();
    }
}
