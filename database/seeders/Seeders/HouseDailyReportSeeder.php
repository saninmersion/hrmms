<?php


namespace Database\Seeders\Seeders;


use App\Domain\HouseDailyReports\Models\HouseDailyReport;
use App\Domain\HouseDailyReports\Repositories\HouseDailyReportRepository;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use Faker\Generator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;

class HouseDailyReportSeeder extends Seeder
{
    public function run(UserRepository $userRepository, HouseDailyReportRepository $houseDailyReportRepository, DatabaseManager $databaseManager)
    {
        $supervisors = $userRepository->byRole(\App\Infrastructure\Constants\AuthRoles::SUPERVISOR)->all();
        $userData      = [];
        /** @var \Faker\Generator $faker */
        $faker = app()->make(Generator::class);
        foreach ($supervisors as $supervisor) {
            for ( $i = 100; $i > 0; $i-- ) {
                $userData[] = [
                        'report_date'                  => now()->subDays($i),
                        'total_surveyed'               => (int) $faker->randomNumber(2),
                        'number_of_houses_in_census'   => (int) $faker->randomNumber(2),
                        'number_of_families_in_census' => (int) $faker->randomNumber(2),
                        'created_by'                   => (int) $supervisor['id'],
                        'district_code'                => (int) $supervisor['district_code'],
                        'census_office_id'             => (int) $supervisor['census_office_id'],
                    ];
            }
        }
        $chunks = array_chunk($userData, 1000);

        foreach ($chunks as $chunk) {
            HouseDailyReport::insert($chunk);
        }
    }
}
