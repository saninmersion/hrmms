<?php

namespace App\Domain\Applications\RedisCache;

use App\Infrastructure\Support\Redis;

/**
 * Class ApplicationStatsRedisCache
 * @package App\Domain\Applications\RedisCache
 */
class ApplicationStatsRedisCache
{
    protected const KEY_PREFIX            = 'stats:';
    public const    OVERALL               = 'overall';
    public const    OVERALL_DISTRICT_WISE = 'overall-district-wise';
    public const    GENDER_BASED          = 'gender-based';
    public const    GENDER_DISTRICT_WISE  = 'gender-district-wise';
    public const    DAILY                 = 'daily';
    public const    DISTRICTS_WISE        = 'districts';
    public const    MUNICIPALITY_WISE     = 'municipality_wise';
    public const    STATS_TIME            = 'stats-time';

    /**
     * @var Redis
     */
    protected Redis $redis;

    /**
     * ApplicationStatsRedisCache constructor.
     *
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param string     $key
     * @param array|null $data
     *
     * @return array|null
     */
    public function cacheArray(string $key, ?array $data = null): ?array
    {
        $key = $this->prepareKey($key);

        if ( !is_null($data) ) {
            $this->redis->set($key, json_encode($data));

            return null;
        }

        return json_decode($this->redis->get($key), true);
    }

    /**
     * @param string      $key
     * @param string|null $data
     *
     * @return string|null
     */
    public function cacheString(string $key, ?string $data = null): ?string
    {
        $key = $this->prepareKey($key);

        if ( !is_null($data) ) {
            $this->redis->set($key, $data);

            return null;
        }

        return $this->redis->get($key) ?: null;
    }

    /**
     * @param string     $key
     * @param int        $districtCode
     * @param array|null $data
     *
     * @return array|null
     */
    public function districtWise(string $key, int $districtCode, ?array $data = null): ?array
    {
        $key = $this->prepareKey($key);

        if ( !is_null($data) ) {
            $this->redis->hSet($key, (string) $districtCode, (string) json_encode($data));
        }

        return json_decode($this->redis->hGet($key, (string) $districtCode), true);
    }

    /**
     * @param array  $stats
     * @param string $municipality
     */
    public function cacheMunicipalityWiseStats(array $stats, string $municipality): void
    {
        $key = $this->prepareKey('municipality-stats');
        $this->redis->hSet($key, $municipality, (string) json_encode($stats));
    }

    /**
     * @param string $municipality
     *
     * @return mixed
     */
    public function getMunicipalityStats(string $municipality)
    {
        $key = $this->prepareKey('municipality-stats');

        return json_decode($this->redis->hGet($key, $municipality), true);
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
