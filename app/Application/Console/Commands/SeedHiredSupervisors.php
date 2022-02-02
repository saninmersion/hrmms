<?php


namespace App\Application\Console\Commands;


use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Domain\Applicants\Repositories\ShortListedApplicantRepository;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\HiringStatus;
use App\Infrastructure\Constants\UserStatus;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;

class SeedHiredSupervisors extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:seed:supervisors';

    /**
     * @var string
     */
    protected $description = 'Seed the hired supervisors.';

    /**
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(ShortListedApplicantRepository $shortListedApplicantRepository, UserRepository $userRepository)
    {
        DB::disableQueryLog();
        try {
            $shortListedApplicantsCursor = $shortListedApplicantRepository->byRole(ApplicationType::SUPERVISOR)->byHiringStatus(
                HiringStatus::STATUS_HIRED
            )->cursor();

            $shortListedApplicantsCursor->each(
                function (ShortListedApplicant $shortListedApplicant) use ($userRepository) {
                    $mobileNumber = $shortListedApplicant->applicationView->mobile_number;
                    $user         = $userRepository->updateOrCreate(
                        [
                            'email'    => sprintf("supervisor_%s@cbs.gov.np", $mobileNumber),
                            'username' => $mobileNumber,
                        ],
                        [
                            'email_verified_at' => now(),
                            'name'              => $shortListedApplicant->applicationView->name_in_english_formatted,
                            'password'          => $shortListedApplicant->municipality_code,
                            'role'              => AuthRoles::SUPERVISOR,
                            'district_code'     => null,
                            'census_office_id'  => null,
                            'status'            => UserStatus::STATUS_ACTIVE,
                        ]
                    );

                    if ( $user ) {
                        /** @var User $user */
                        $this->info(sprintf("%s seeded.", $user->name));
                        $this->newLine();
                    } else {
                        $this->error(sprintf("Error seeding %s.", $shortListedApplicant->applicationView->name_in_english_formatted));
                        $this->newLine();
                    }
                }
            );
        } catch (\Exception $e) {
            $this->error("Error seeding hired supervisors.");
        }

        DB::enableQueryLog();
    }
}
