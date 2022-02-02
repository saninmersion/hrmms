<?php

namespace Database\Seeders\Fakers;

use App\Domain\Users\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class UserFaker
 * @package Database\Seeders\Fakers
 */
class UserFaker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(rand(10, 25))->create();
    }
}
