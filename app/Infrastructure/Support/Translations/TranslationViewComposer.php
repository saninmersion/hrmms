<?php

namespace App\Infrastructure\Support\Translations;

use Illuminate\Contracts\View\View;

/**
 * Class TranslationViewComposer
 * @package App\Infrastructure\Support\Translations
 */
class TranslationViewComposer
{
    /**
     * @var TranslationService
     */
    protected TranslationService $translationService;

    /**
     * TranslationViewComposer constructor.
     *
     * @param TranslationService $translationService
     */
    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * @param View $view
     */
    public function compose(View $view): void
    {
        $view->with(
            [
                'trans'  => $this->translationService->getTrans(),
                'locale' => app()->getLocale(),
            ]
        );
    }
}
