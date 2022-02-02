<?php

use App\Application\Admin\Controllers\ApplicantsController;
use App\Application\Front\Support\ApplicationFormOptions;
use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Models\ApplicationListView;
use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Domain\Applicants\Presenters\FrontFormPresenter;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Repositories\ShortListedApplicantRepository;
use App\Domain\Applications\Models\Application;
use App\Domain\Applications\Services\StatsService;
use App\Domain\CensusOffices\Repositories\CensusOfficeRepository;
use App\Domain\HouseDailyReports\Repositories\HouseDailyReportRepository;
use App\Domain\Users\Actions\ChangeUserPassword;
use App\Domain\Users\DTO\UserChangePasswordDto;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserEloquentRepository;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\HiringStatus;
use Database\Seeders\Seeders\CensusAreaSeeder;
use Database\Seeders\Seeders\CensusOfficeMonitorsSeeder;
use Database\Seeders\Seeders\HouseDailyReportSeeder;
use Database\Seeders\Seeders\SupervisorUserSeeder;
use Faker\Factory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

Route::get(
    'migrate',
    function () {
        Artisan::call('migrate');

        return 'success';
    }
);

Route::get('route-list', function () {
    $routes =  \Illuminate\Support\Facades\Route::getRoutes();
    dump($routes);
});

Route::get(
    'exports',
    function () {
        Artisan::call('cbs:applications:export');

        return 'success';
    }
);

Route::get(
    'assign-to-verifiers',
    function () {
        Artisan::call('cbs:applications:assign-to-verifiers 4');

        return 'success';
    }
);

Route::get(
    'shortlist-hire-reject',
    function (
        ApplicationListView $applicationListView,
        DistrictRepository $districtRepository,
        ShortListedApplicantRepository $shortListedApplicantRepository,
        ShortListedApplicant $shortListedApplicant
    ) {
        $districts = $districtRepository->getByDistrictCode(101);
        /** @var Builder $model */
        $model = $applicationListView;

        /** @var Builder $shortlistedModel */
        $shortlistedModel = $shortListedApplicant;

        $municipalities = collect($districts['municipalities']);

        $municipalities->each(
            function ($municipality) use ($model, $shortlistedModel) {
                $model      = $model->where(
                    function ($query) use ($municipality) {
                        /** @var Builder $query */
                        $query->orWhere('first_priority_municipality_code', $municipality);
                        $query->orWhere('second_priority_municipality_code', $municipality);
                        $query->orWhere('permanent_address_municipality_code', $municipality);
                        $query->orWhere('temporary_address_municipality_code', $municipality);
                    }
                );
                $applicants = $model->get(['id', 'application_for']);
                $faker      = Factory::create();
                $applicants->each(
                    function ($applicant) use ($shortlistedModel, $municipality, $faker) {
                        $applicationFor = $applicant['application_for'];

                        $role = ApplicationType::ENUMERATOR;

                        if ( $applicationFor === ApplicationType::ENUMERATOR_SUPERVISOR ) {
                            $role = $faker->randomElement([ApplicationType::ENUMERATOR, ApplicationType::SUPERVISOR]);
                        }
                        if ( $applicationFor === ApplicationType::SUPERVISOR ) {
                            $role = ApplicationType::SUPERVISOR;
                        }
                        $isShortlisted = $faker->boolean(80);
//                            dd($isShortlisted);
//                            dd($applicant['id']);
                        $shortlistApplicantDetails = [
                            'applicant_id'      => $applicant['id'],
                            'municipality_code' => $municipality['code'],
                            'role'              => $role,
                        ];
                        $shortlisted               = [
                            'is_shortlisted' => $isShortlisted,
                            'hiring_status'  => $isShortlisted ? $faker->randomElement([HiringStatus::STATUS_HIRED, HiringStatus::STATUS_REJECTED])
                                : null,
                        ];
//                            dd($shortlistApplicantDetails, $shortlisted);
                        $shortlistedModel->updateOrCreate(
                            $shortlistApplicantDetails,
                            $shortlisted
                        );
                    }
                );
            }
        );
    }
);

Route::get(
    '/refresh',
    function () {
        Artisan::call('cbs:refresh:application-list');

        return 'success';
    }
);

Route::get(
    '/offline-form',
    function (
        ApplicantRepository $applicantRepository,
        ApplicantsController $controller
    ) {
        $applicantRepository->setPresenter(FrontFormPresenter::class);
        $applicant      = $applicantRepository->find(51213);
        $data           = ApplicationFormOptions::formOptions();
        $mediaUploadUrl = route('front.application.files.upload');

        $data['mediaUploadUrl'] = $mediaUploadUrl;
        $data['applicant']      = $applicant;
        $data['foundStatus']    = '';

        return inertia('Applications/CreateApplication', $data);
    }
);

Route::get(
    '/applicant-factory',
    function (DistrictRepository $districtRepository, \Faker\Generator $faker) {
        $districts      = District::with('municipalities')->pluck('code')->toArray();
        $municipalities = Municipality::all()->pluck('code')->toArray();

        for ( $i = 0; $i < 1000; $i++ ) {

            $applicant = Applicant::factory()->locations($districts, $municipalities)->create();
            /** @var District $district */
            $district               = District::where("code", '=', "101")->firstOrFail();
            $districtMunicipalities = $district->municipalities()->pluck('code')->toArray();
            $application            = Application::factory()->create(
                [
                    'applicant_id' => $applicant->id,
                    'locations'    => [
                        'first'  => [
                            'district'     => $district->code,
                            'municipality' => $faker->randomElement($districtMunicipalities),
                            'ward'         => $faker->numberBetween(1, 7),
                        ],
                        'second' => [
                            'district'     => $district->code,
                            'municipality' => $faker->randomElement($districtMunicipalities),
                            'ward'         => $faker->numberBetween(1, 7),
                        ],
                    ],

                ]
            );
        }

    }
);

Route::get(
    '/census-office-seeder',
    function (CensusAreaSeeder $seeder) {
        $seeder->run();
    }
);

Route::get(
    '/census-office-monitor-seeder',
    function (
        CensusOfficeMonitorsSeeder $seeder,
        CensusOfficeRepository $repository,
        UserRepository $userRepository
    ) {
        $seeder->run($repository, $userRepository);
    }
);

Route::get(
    '/user-factory',
    function (
        UserRepository $userRepository,
        DistrictRepository $districtRepository,
        \Faker\Generator $faker
    ) {
        $roles     = AuthRoles::all();
        $districts = $districtRepository->allDistrictForOptions()->pluck('code');
        DB::beginTransaction();
        foreach ( $roles as $role ) {
            for ( $i = 1; $i <= 50; $i++ ) {
                $userId = "dev_{$role}_{$i}";
                /** @var \App\Domain\Users\Models\User $user */
                $user = $userRepository->firstOrNew(['username' => $userId]);
                if ( !$user->id ) {
                    $user->role              = $role;
                    $user->name              = $faker->name;
                    $user->password          = "password";
                    $user->email             = "{$userId}@dev.test";
                    $user->email_verified_at = now();

                    if ( $role === AuthRoles::DISTRICT_STAFFS ) {
                        $user->district_code = (int) $districts->random();
                    }
                    $user->save();
                }
            }
        }
        DB::commit();
    }
);

Route::get(
    '/supervisor-factory',
    function (
        UserRepository $userRepository,
        CensusOfficeRepository $censusOfficeRepository,
        \Faker\Generator $faker,
        SupervisorUserSeeder $seeder
    ) {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '3000');
        $seeder->run($censusOfficeRepository, $userRepository, $faker);
    }
);

Route::get(
/**
 * @throws \Throwable
 * @throws \Illuminate\Contracts\Container\BindingResolutionException
 */ '/house-daily-report-factory',
    function (
        DatabaseManager $databaseManager,
        HouseDailyReportRepository $houseDailyReportRepository,
        UserRepository $userRepository,
        HouseDailyReportSeeder $seeder
    ) {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '3000');

        $seeder->run($userRepository, $houseDailyReportRepository, $databaseManager);
    }
);


Route::get(
    '/districts',
    function (DatabaseManager $databaseManager, StatsService $statsService) {
        $districtCode = 101;
        $databaseManager->enableQueryLog();

        $statsService->overallByDistrict()->set();
        $statsService->genderBasedByDistrict()->set();
        $statsService->municipalityStatsByDistrict()->set();

        $overall           = $statsService->overallByDistrict((int) $districtCode)->get();
        $genderBased       = $statsService->genderBasedByDistrict((int) $districtCode)->get();
        $municipalityBased = $statsService->municipalityStatsByDistrict((int) $districtCode)->get();

        dd($overall, $genderBased, $municipalityBased, $databaseManager->getQueryLog());
    }
);

Route::get(
    '/dashboard',
    function (DatabaseManager $databaseManager, StatsService $statsService) {
        $databaseManager->enableQueryLog();

        $statsService->overall()->set();
        $statsService->genderBased()->set();
        $statsService->dailyTrend()->set();
        $statsService->districtWise()->set();
        $statsService->statsDateTime()->set();

        $overallStats = $statsService->overall()->get();
        $gender       = $statsService->genderBased()->get();
        $daily        = $statsService->dailyTrend()->get();
        $districts    = $statsService->districtWise()->get();
        $dateTime     = $statsService->statsDateTime()->get();

        dd($overallStats, $gender, $daily, $districts, $dateTime, $databaseManager->getQueryLog());
    }
);

Route::get(
    '/seed-supervisor',
    function (ShortListedApplicantRepository $shortListedApplicantRepository, UserEloquentRepository $userRepository) {
        $shortListedApplicantsCursor = $shortListedApplicantRepository->byRole(ApplicationType::SUPERVISOR)->byHiringStatus(
            HiringStatus::STATUS_HIRED
        )->cursor();

        $shortListedApplicantsCursor->each(
            function (ShortListedApplicant $shortListedApplicant) use ($userRepository) {
                $mobileNumber = $shortListedApplicant->applicationView->mobile_number;
                $userRepository->updateOrCreate(
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
                        'status'            => null,
                    ]
                );
            }
        );
    }
);
