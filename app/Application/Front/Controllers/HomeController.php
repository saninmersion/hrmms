<?php

namespace App\Application\Front\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Infrastructure\Support\BikramSambat\BikramSambat;
use App\Infrastructure\Support\BikramSambat\InvalidDateException;
use App\Infrastructure\Support\Controller\FrontController;
use App\Infrastructure\Support\Unicode\NepaliNumber;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class HomeController
 * @package App\Application\Front\Controllers
 */
class HomeController extends FrontController
{
    /**
     * @param DistrictRepository $districtRepository
     *
     * @return Application|Factory|View
     * @throws InvalidDateException
     */
    public function __invoke(DistrictRepository $districtRepository)
    {
        $deadline  = $this->formatDeadline(config('config.deadline'));
        $districts = $districtRepository->allDistrictForOptions();

        return view('front.home.index', compact('deadline', 'districts'));
    }

    /**
     * @param string $dateInBs
     *
     * @return array
     * @throws InvalidDateException
     * @throws \Exception
     */
    protected function formatDeadline(string $dateInBs): array
    {
        $locale = app()->getLocale();

        if ( $locale === 'en' ) {
            $dateInAd = BikramSambat::bsToAd($dateInBs);

            return [
                'raw'       => $dateInAd,
                'formatted' => $dateInAd ? $dateInAd->format('Y M d') : '',
            ];
        }

        $dateArray = array_map('trim', (array) preg_split("/[\D|\s]/", $dateInBs));

        $dateString = sprintf("%s %s %s", $dateArray[0], BikramSambat::bsMonthText((int) $dateArray[1]), $dateArray[2]);

        return [
            'raw'       => BikramSambat::bsToAd($dateInBs),
            'formatted' => NepaliNumber::englishToNepali($dateString),
        ];
    }
}
