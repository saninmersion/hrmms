<?php

namespace App\Application\Console\Commands\Imports;

use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\UserStatus;
use App\Infrastructure\Support\Exceptions\FileNotFoundException;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 *
 */
class ImportUsersFromCSV extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:import:users';

    /**
     * @var string
     */
    protected $description = 'Import users from csv.';

    /**
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(UserRepository $userRepository)
    {
        DB::disableQueryLog();

        try {
            if ( !Storage::disk('imports')->exists('users.xlsx') ) {
                throw new FileNotFoundException();
            }

            $this->info("Importing excel data in memory");
            $users = (new FastExcel())->import(storage_path('imports/users.xlsx'));
            $this->info("Excel Data saved into memory");
            foreach ($users->chunk(100) as $chunk) {

                foreach ($chunk as $row) {
                    $username = data_get($row, 'username', '');
                    $name     = data_get($row, 'name', data_get($row, 'username', ''));
                    $email    = data_get($row, 'email', sprintf("supervisor_%s@cbs.gov.np", $username));

                    $user = $userRepository->updateOrCreate(
                        [
                            'email'    => $email,
                            'username' => $username,
                        ],
                        [
                            'name'              => $name,
                            'password'          => data_get($row, 'password', 'password'),
                            'role'              => AuthRoles::SUPERVISOR,
                            'district_code'     => data_get($row, 'district_code'),
                            'census_office_id'  => data_get($row, 'census_office_id'),
                            'status'            => UserStatus::STATUS_ACTIVE,
                            'email_verified_at' => now(),
                        ]
                    );

                    $this->info(sprintf('Imported User %s', $user->username));
                }
            }
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());
            $this->error("Error importing users.");
        }

        DB::enableQueryLog();
    }
}
