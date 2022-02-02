<?php


namespace Database\Seeders\Seeders;


use App\Domain\CensusOffices\Repositories\CensusOfficeRepository;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupervisorUserSeeder extends Seeder
{
    public function run(CensusOfficeRepository $censusOfficeRepository, UserRepository $userRepository, Generator $faker)
    {
        $censusOffices = $censusOfficeRepository->all(['id', 'district_code']);
        $userData      = [];

        foreach ( $censusOffices as $censusOffice ) {
            $id = $censusOffice['id'];
            for ( $i = 1; $i <= 100; $i++ ) {
                $userId = "dev_supervisor_{$id}_{$i}";
                /** @var \App\Domain\Users\Models\User $user */
                $userData[] = [
                    'username'          => $userId,
                    'email'             => "{$userId}@dev.test",
                    'role'              => AuthRoles::SUPERVISOR,
                    'name'              => $userId,
                    'password'          => Hash::make("password"),
                    'email_verified_at' => now(),
                    'census_office_id'  => $id,
                    'district_code'     => $censusOffice['district_code'],
                ];
            }
        }

        $chunks = array_chunk($userData, 1000);

        foreach ($chunks as $chunk) {
            User::insert($chunk);
        }
    }
}
