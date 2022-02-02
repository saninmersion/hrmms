<?php

namespace Database\Seeders\Fakers;

use App\Domain\Applications\Models\Application;
use Illuminate\Database\Seeder;

/**
 * Class ApplicationFaker
 *
 * @package Database\Seeders\Fakers
 */
class ApplicationFaker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Application::factory()->create();
    }
}
