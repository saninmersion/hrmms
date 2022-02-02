<?php


namespace App\Application\Console\Commands\Imports;


use App\Domain\Applicants\Models\Applicant;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\UserStatus;
use App\Infrastructure\Support\Exceptions\FileNotFoundException;
use App\Infrastructure\Support\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Exception;
use League\Csv\Reader;

class ImportHiredSupervisors extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:supervisor:seed-from-csv';

    /**
     * @var string
     */
    protected $description = 'Seed supervisors from CSV';

    /**
     * @var array
     */
    protected array $columns = [
        'applicant_id',
        'mobile_number',
    ];

    /**
     * @var string
     */
    protected string $file = 'hiring/supervisor_test.csv';

    /**
     * @param UserRepository $userRepository
     */
    public function handle(UserRepository $userRepository): void
    {
        DB::disableQueryLog();
        try {
            if ( !Storage::disk('imports')->exists($this->file) ) {
                throw new FileNotFoundException();
            }
            $reader = $this->getFile($this->file);

            foreach ($reader as $row) {
                $applicant = Applicant::find($row['applicant_id']);

                if ( $applicant ) {
                    /** @var Applicant $applicant */
                    /** @var User $user */
                    $user = $userRepository->updateOrCreate(
                        [
                            'email'    => sprintf("supervisor_%s@cbs.gov.np", $applicant->mobile_number),
                            'username' => $applicant->mobile_number,
                        ],
                        [
                            'email_verified_at' => now(),
                            'name'              => $applicant->name_in_english_formatted,
                            'password'          => 'password',
                            'role'              => AuthRoles::SUPERVISOR,
                            'district_code'     => null,
                            'census_office_id'  => null,
                            'status'            => UserStatus::STATUS_ACTIVE,
                        ]
                    );

                    $this->info(sprintf('%s with id %s was hired.', $user->name, $row['applicant_id']));
                    $this->newLine();

                    continue;
                }

                $this->error(sprintf('%s with id %s was not found.', $row['mobile_number'], $row['applicant_id']));
                $this->newLine();
            }
        } catch (FileNotFoundException $exception) {
            $this->error($exception->getMessage());
        } catch (\Exception $exception) {
            Helper::logException($exception);
        }

        DB::enableQueryLog();
    }

    /**
     * @param string $fileName
     *
     * @return Reader
     * @throws Exception
     */
    protected function getFile(string $fileName): Reader
    {
        $reader = Reader::createFromPath(storage_path(sprintf("imports/%s", $fileName)), 'r');
        $reader->setHeaderOffset(0);

        return $reader;
    }
}
