<?php

namespace App\Application\Console\Commands;

use App\Domain\CensusOffices\Models\CensusOffice;
use App\Domain\CensusOffices\Repositories\CensusOfficeRepository;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\UserStatus;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeedCensusAreaSupervisors extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:seed:census_supervisors';

    /**
     * @var string
     */
    protected $description = 'Seed the census supervisors.';

    /**
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(CensusOfficeRepository $censusOfficeRepository, UserRepository $userRepository)
    {
        DB::disableQueryLog();
        try {
            $censusAreas = $censusOfficeRepository->all();

            $censusAreas->each(
                function (CensusOffice $censusOffice) use ($userRepository) {
                    $username = Str::lower(Str::snake((string) str_replace(['(', ')', '-'], '', $censusOffice->office_name)));

                    $user = $userRepository->updateOrCreate(
                        [
                        'email'    => sprintf("%s_sup@cbs.gov.np", $username),
                        'username' => sprintf("%s_sup", $username),
                        ],
                        [
                        'name'              => sprintf("Supervisor %s", $censusOffice->office_name),
                        'password'          => $username,
                        'role'              => AuthRoles::SUPERVISOR,
                        'district_code'     => $censusOffice->district_code,
                        'census_office_id'  => $censusOffice->id,
                        'status'            => UserStatus::STATUS_ACTIVE,
                        'email_verified_at' => now(),
                        ]
                    );

                    if ( $user ) {
                        /** @var User $user */
                        $this->info(sprintf("%s seeded.", $user->name));
                        $this->newLine();
                    } else {
                        $this->error(sprintf("Error seeding %s.", $username));
                        $this->newLine();
                    }
                }
            );
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());
            $this->error("Error seeding census supervisors.");
        }

        DB::enableQueryLog();
    }
}
