<?php

namespace Database\Seeders\Seeders;

use App\Domain\CensusOffices\Models\CensusOffice;
use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\Reader;

/**
 * Class CensusAreaSeeder
 * @package Database\Seeders\Seeders
 */
class CensusAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $reader = $this->getFile('imports/census-area.csv');

        $censusOffices = collect();
        foreach ($reader as $row) {
            $censusOffices->push($row);
        }

        $censusOffices->each(
            function ($office) {
                CensusOffice::updateOrCreate($office);
            }
        );
    }

    /**
     * @param string $fileName
     *
     * @return Reader
     * @throws Exception
     */
    protected function getFile(string $fileName): Reader
    {
        $reader = Reader::createFromPath(storage_path($fileName), 'r');
        $reader->setHeaderOffset(0);

        return $reader;
    }

}
