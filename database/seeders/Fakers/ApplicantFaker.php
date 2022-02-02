<?php

namespace Database\Seeders\Fakers;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applications\Models\Application;
use Illuminate\Database\Seeder;

/**
 * Class ApplicantFaker
 * @package Database\Seeders\Fakers
 */
class ApplicantFaker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $districts = District::all()->pluck('code')->toArray();
        $municipalities = Municipality::all()->pluck('code')->toArray();

        Applicant::factory()->count(rand(100, 200))->locations($districts, $municipalities)->has(
            Application::factory()->locations($districts, $municipalities)
        )->create();
    }
}
