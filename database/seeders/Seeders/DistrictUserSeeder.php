<?php

namespace Database\Seeders\Seeders;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class DistrictUserSeeder
 * @package Database\Seeders\Seeders
 */
class DistrictUserSeeder extends Seeder
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
        foreach (District::where('is_old_district', false)->cursor() as $district) {
            $districtName = Str::lower($district->title_en);
            $email        = sprintf('%s@cbs.gov.np', $districtName);
            /** @var Collection $users */
            $users = $userRepository->findWhere(['email' => $email]);

            if ( $users->isEmpty() ) {
                User::factory()->districtStaff()->create(
                    [
                        'name'          => sprintf("%s User", $district->title_en),
                        'username'      => sprintf("cbs_%s", $districtName),
                        'email'         => $email,
                        'password'      => sprintf("%s_cbs", $districtName),
                        'district_code' => $district->code,
                    ]
                );
            }
        }
    }
}
