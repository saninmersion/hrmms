<?php

namespace Database\Seeders\Seeders;

use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use Illuminate\Database\Seeder;

/**
 * Class SuperAdminUserSeeder
 * @package Database\Seeders\Seeders
 */
class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param UserRepository $userRepository
     *
     * @return void
     */
    public function run(UserRepository $userRepository)
    {
        $users = $userRepository->byRole(AuthRoles::SUPER_ADMIN)->getBuilder()->count();

        if ( $users === 0 ) {
            User::factory()->superAdmin()->create();
        }
    }
}
