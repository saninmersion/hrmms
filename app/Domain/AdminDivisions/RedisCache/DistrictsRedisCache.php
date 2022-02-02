<?php

namespace App\Domain\AdminDivisions\RedisCache;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Infrastructure\Support\Redis;
use Illuminate\Support\Collection;

/**
 * Class DistrictsRedisCache
 * @package App\Domain\AdminDivisions\RedisCache
 */
class DistrictsRedisCache
{
    public const KEY_PREFIX = 'districts:';

    /**
     * @var Redis
     */
    protected Redis $redis;

    /**
     * DistrictsRedisCache constructor.
     *
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param Collection $districts
     *
     * @return Collection
     */
    public function cacheAllDistrict(Collection $districts): Collection
    {
        return $districts->map(
            function (District $district) {
                $key = $this->prepareKey('all');

                $this->redis->hSet(
                    $key,
                    (string) $district->code,
                    json_encode(
                        [
                            'title_en' => $district->title_en,
                            'title_ne' => $district->title_ne,
                        ]
                    ) ?: ''
                );

                return [
                    'code'     => $district->code,
                    'title_en' => $district->title_en,
                    'title_ne' => $district->title_ne,
                ];
            }
        )->sortBy('code');
    }

    /**
     * @return Collection
     */
    public function getAllDistricts(): Collection
    {
        $districts = $this->redis->hGetAll($this->prepareKey('all'));

        return collect($districts)->map(
            function (string $name, string $code) {
                return array_merge(
                    [
                        'code' => $code,
                    ],
                    json_decode($name, true)
                );
            }
        )->sortBy('code')->values();
    }

    public function getAllDistrictsWithMunicipalities(): Collection
    {
        $districts = $this->redis->hGetAll($this->prepareKey('all-municipalities'));

        return collect($districts)->map(
            function (string $districtWithMunicipalities, string $code) {
                return array_merge(
                    [
                        'code' => $code,
                    ],
                    json_decode($districtWithMunicipalities, true)
                );
            }
        )->sortBy('code')->values();
    }

    /**
     * @param int $districtCode
     *
     * @return array|null
     */
    public function getByDistrictCode(int $districtCode): ?array
    {
        $key = $this->prepareKey('all-municipalities');

        return json_decode($this->redis->hGet($key, (string) $districtCode), true);
    }

    public function cacheAllDistrictWithMunicipalities(Collection $districts): Collection
    {
        return $districts->map(
            function (District $district) {
                $key = $this->prepareKey('all-municipalities');

                $municipalities = $district->municipalities->map(
                    function (Municipality $municipality) {
                        return [
                            'code'     => $municipality->code,
                            'title_en' => $municipality->title_en,
                            'title_ne' => $municipality->title_ne,
                            'wards'    => $municipality->total_wards,
                        ];
                    }
                );

                $this->redis->hSet(
                    $key,
                    (string) $district->code,
                    json_encode(
                        [
                            'title_en'       => $district->title_en,
                            'title_ne'       => $district->title_ne,
                            'municipalities' => $municipalities,
                        ]
                    ) ?: ''
                );

                return [
                    'code'           => $district->code,
                    'title_en'       => $district->title_en,
                    'title_ne'       => $district->title_ne,
                    'municipalities' => $municipalities,
                ];
            }
        )->sortBy('code');
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function prepareKey(string $key): string
    {
        return sprintf("%s:%s", self::KEY_PREFIX, $key);
    }
}
