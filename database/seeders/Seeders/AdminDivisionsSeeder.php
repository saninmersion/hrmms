<?php

namespace Database\Seeders\Seeders;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\AdminDivisions\Models\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use League\Csv\Exception;
use League\Csv\Reader;

/**
 * Class AdminDivisionsSeeder
 * @package Database\Seeders\Seeders
 */
class AdminDivisionsSeeder extends Seeder
{
    /**
     * @var string
     */
    protected string $file = 'imports/municipalities.csv';

    /**
     * @var array
     */
    protected array $columns = [
        'Province'         => 'province_code',
        'Province Name En' => 'province_en',
        'Province Name Ne' => 'province_ne',
        'District Id'      => 'dist_id',
        'District Code'    => 'district_code',
        'District Name En' => 'district_en',
        'District Name Ne' => 'district_ne',
        'GAPA Id'          => 'mun_id',
        'GAPA Code'        => 'mun_code',
        'GaPa Name En'     => 'mun_en',
        'GaPa Name Ne'     => 'mun_ne',
        'Total Wards'      => 'total_wards',
    ];

    /**
     * @var array
     */
    protected array $csvDbColumnsMappings = [
        'provinces'      => [
            'province_code' => 'code',
            'province_en'   => 'title_en',
            'province_ne'   => 'title_ne',
        ],
        'districts'      => [
            'province_code' => 'province_code',
            'dist_id'       => 'dist_id',
            'district_code' => 'code',
            'district_en'   => 'title_en',
            'district_ne'   => 'title_ne',
        ],
        'municipalities' => [
            'province_code' => 'province_code',
            'district_code' => 'district_code',
            'mun_id'        => 'mun_id',
            'mun_code'      => 'code',
            'mun_en'        => 'title_en',
            'mun_ne'        => 'title_ne',
            'total_wards'   => 'total_wards',
        ],
    ];

    /**
     * @var array
     */
    protected array $oldDistricts = [
        [
            'dist_id'         => '78',
            'code'            => '901',
            'title_en'        => 'Nawalparasi (Old)',
            'title_ne'        => 'नवलपरासी (पुरानो)',
            'is_old_district' => true,
        ],
        [
            'dist_id'         => '79',
            'code'            => '902',
            'title_en'        => 'Rukum (Old)',
            'title_ne'        => 'रुकुम (पुरानो)',
            'is_old_district' => true,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $reader = $this->getFile($this->file);

        $municipalities = collect();
        foreach ($reader as $row) {
            $municipalities->push($this->prepareAttribute($row));
        }

        $provinces = $municipalities->unique(fn(Collection $row) => $row->get('province_code'));
        $districts = $municipalities->unique(fn(Collection $row) => $row->get('district_code'));

        $this->seedData($provinces, 'provinces');
        $this->seedData($districts, 'districts');
        $this->seedData($municipalities, 'municipalities');
        $this->seedOldDistricts();
    }

    /**
     * Seed Province
     *
     * @param Collection $rows
     * @param string     $type
     */
    protected function seedData(Collection $rows, string $type): void
    {
        /** @var Builder $model */
        $model = [
                     'provinces'      => app(Province::class),
                     'districts'      => app(District::class),
                     'municipalities' => app(Municipality::class),
                 ][$type];

        $rows->each(
            function (Collection $data) use ($type, $model) {
                $row = $this->prepareColumns($data, $this->csvDbColumnsMappings[$type]);

                $model->updateOrCreate(['code' => $row['code']], $row);
            }
        );
    }

    protected function seedOldDistricts(): void
    {
        /** @var Builder $model */
        $model = app(District::class);

        collect($this->oldDistricts)->each(
            fn(array $district) => $model->updateOrCreate(['code' => $district['code']], $district)
        );
    }

    /**
     * @param Collection $data
     * @param array      $columnsMapping
     *
     * @return array
     */
    protected function prepareColumns(Collection $data, array $columnsMapping): array
    {
        return $data->only(array_keys($columnsMapping))->flatMap(
            fn($val, $key) => [
                $columnsMapping[$key] => $val,
            ]
        )->toArray();
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

    /**
     * @param array $row
     *
     * @return Collection
     */
    protected function prepareAttribute(array $row): Collection
    {
        return collect($row)->map(fn($col, $key) => [$this->columns[$key] ?? $key => $col])->collapse();
    }
}
