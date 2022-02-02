<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\ShortListedApplicantDetailTransformer;
use App\Domain\Applicants\Transformers\ShortListedApplicantDistrictTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ShortListedApplicantDistrictPresenter
 * @package App\Domain\Applicants\Presenters
 */
class ShortListedApplicantDistrictPresenter extends FractalPresenter
{
    public function getTransformer(): ShortListedApplicantDistrictTransformer
    {
        return new ShortListedApplicantDistrictTransformer();
    }
}
