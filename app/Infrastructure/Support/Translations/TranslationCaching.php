<?php

namespace App\Infrastructure\Support\Translations;

use Illuminate\Cache\CacheManager;
use Illuminate\Support\Collection;

/**
 * Class TranslationCaching
 * @package App\Infrastructure\Support\Translations
 */
class TranslationCaching
{
    /**
     * @var array
     */
    protected array $availableLocales = ['en', 'ne'];

    /**
     * @param string $locale
     *
     * @return Collection
     */
    public function cacheTrans(string $locale): Collection
    {
        return (new CacheManager(app()))->rememberForever(
            sprintf('lang-%s.js', $locale),
            function () use ($locale) {
                $files = glob(resource_path(sprintf("lang/%s/*.php", $locale)));

                return collect($files)->flatMap(
                    function (string $file) {
                        $fileName = basename($file, '.php');

                        return [$fileName => include $file];
                    }
                );
            }
        );
    }

    /**
     * Cache all available translations
     */
    public function cacheAll(): void
    {
        collect($this->availableLocales)->each(
            function (string $locale) {
                $this->cacheTrans($locale);
            }
        );
    }

    /**
     * @param string $locale
     */
    public function flushCache(string $locale): void
    {
        (new CacheManager(app()))->forget(sprintf('lang-%s.js', $locale));
    }

    /**
     * Flush all translation cache
     */
    public function flushAll(): void
    {
        collect($this->availableLocales)->each(
            function (string $locale) {
                $this->flushCache($locale);
            }
        );
    }
}
