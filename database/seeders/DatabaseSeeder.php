<?php

namespace Database\Seeders;

use Database\Seeders\Fakers\ApplicantFaker;
use Database\Seeders\Fakers\UserFaker;
use Database\Seeders\Seeders\AdminDivisionsSeeder;
use Database\Seeders\Seeders\CensusAreaSeeder;
use Database\Seeders\Seeders\SuperAdminUserSeeder;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * @var array|string[]
     */
    protected array $seeders = [
        SuperAdminUserSeeder::class,
        AdminDivisionsSeeder::class,
        CensusAreaSeeder::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $fakers = [
        UserFaker::class,
        ApplicantFaker::class,
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call($this->seeders);

        // Fakers
        if ( app()->environment() === 'production' ) {
            return;
        }

        $confirmation = $this->command->confirm(
            'Do you wish to run fakers? Fakers create dummy data for test purpose.'
        );
        if ( $confirmation ) {
            $this->call($this->fakers);
        }
    }
}
