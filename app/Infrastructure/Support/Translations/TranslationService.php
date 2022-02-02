<?php

namespace App\Infrastructure\Support\Translations;

use Illuminate\Support\Collection;

/**
 * Class TranslationService
 * @package App\Infrastructure\Support\Translations
 */
class TranslationService
{
    /**
     * @return Collection
     */
    public function getTrans(): Collection
    {
        /** @var TranslationCaching $translationCaching */
        $translationCaching = app(TranslationCaching::class);
        $currentLang        = app()->getLocale();

        if ( app()->isLocal() ) {
            $translationCaching->flushCache($currentLang);
        }

        return $translationCaching->cacheTrans($currentLang);
    }
}
