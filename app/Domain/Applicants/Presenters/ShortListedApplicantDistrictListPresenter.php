<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\ShortListedApplicantDistrictListTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ShortListedApplicantDistrictListPresenter
 * @package App\Domain\Applicants\Presenters
 */
class ShortListedApplicantDistrictListPresenter extends FractalPresenter
{
    public function getTransformer(): ShortListedApplicantDistrictListTransformer
    {
        return new ShortListedApplicantDistrictListTransformer();
    }
}
