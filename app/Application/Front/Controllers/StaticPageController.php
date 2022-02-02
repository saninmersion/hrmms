<?php

namespace App\Application\Front\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Infrastructure\Support\Controller\FrontController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class StaticPageController
 *
 * @package App\Application\Front\Controllers
 */
class StaticPageController extends FrontController
{
    /**
     * @return Factory|View
     */
    public function howTo()
    {
        return view('front.static.howto');
    }

    /**
     * @return Factory|View
     */
    public function privacyPolicy()
    {
        return view('front.static.privacy_policy');
    }

    /**
     * @return Factory|View
     */
    public function termsAndConditions()
    {
        return view('front.static.terms-and-conditions');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function viewSupervisorShortlist()
    {
        $districtRepository = app(DistrictRepository::class);
        $districts          = $districtRepository->allDistrictsWithMunicipalitiesOptions()->values()->toArray();

        return view('front.static.supervisor_shortlist', compact('districts'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function viewEnumeratorShortlist()
    {
        $districtRepository = app(DistrictRepository::class);
        $districts          = $districtRepository->allDistrictsWithMunicipalitiesOptions()->values()->toArray();

        return view('front.static.enumerator_shortlist', compact('districts'));
    }
}
