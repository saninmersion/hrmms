<?php

namespace App\Application\Utils\Controllers;

use App\Infrastructure\Constants\General;
use App\Infrastructure\Support\Controller\FrontController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class LocaleController
 * @package App\Application\Utils\Controllers
 */
class LocaleController extends FrontController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $request->input('locale', config('config.default-locale'));

        if ( !in_array($locale, ['ne', 'en']) ) {
            return $this->sendError('Invalid locale.', Response::HTTP_BAD_REQUEST);
        }

        session()->put(General::LOCALE_SESSION_KEY, $locale);

        return $this->sendResponse(null, 'Locale set successfully.');
    }
}
