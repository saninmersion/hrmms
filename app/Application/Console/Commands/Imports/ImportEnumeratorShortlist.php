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
 * Class ImportEnumeratorShortlist
 * @package App\Application\Console\Commands\Imports
 */
class ImportEnumeratorShortlist extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:shortlist:enumerator';

    /**
     * @var string
     */
    protected $description = 'Import shortlisted enumerator applications list from excel';

    /**
     * @return void
     */
    public function handle()
    {
        DB::connection()->disableQueryLog();

        try {
            if ( !Storage::disk('imports')->exists('shortlist/all-shortlisted-enumerators-yipl-import.xlsx') ) {
                throw new FileNotFoundException();
            }

            $this->info("Importing excel data in memory");
            $shortlist = (new FastExcel)->import(storage_path('imports/shortlist/all-shortlisted-enumerators-yipl-import.xlsx'));
            $this->info("Excel Data saved into memory");

            foreach ($shortlist->chunk(100) as $chunk) {
                foreach ($chunk as $index => $line) {
                    $applicant = Applicant::find($line['id']);

                    if ( $applicant ) {
                        ShortListedApplicant::firstOrCreate(
                            [
                                'applicant_id' => $line['id'],
                                'role'         => ApplicationType::ENUMERATOR,
                            ],
                            [
                                'municipality_code' => $line['code'],
                                'is_shortlisted'    => true,
                                'rank'              => $line['rank'],
                                'score'             => $line['total_score'] ?? 0,
                                'hiring_status'     => null,
                                'metadata'          => [
                                    'ward'  =>  $line['ward']
                                ],
                            ]
                        );

                        $this->info(sprintf('Enumerator with id %s was shortlisted.', $line['id']));
                        $this->newLine();

                        continue;
                    }

                    $this->error(sprintf('Enumerator with id %s was not found.', $line['id']));
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
