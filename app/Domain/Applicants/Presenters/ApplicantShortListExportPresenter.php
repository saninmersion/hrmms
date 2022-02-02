<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\ApplicantShortListExportTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ApplicantShortListExportPresenter
 * @package App\Domain\Applicants\Presenters
 */
class ApplicantShortListExportPresenter extends FractalPresenter
{
    /**
     * @return ApplicantShortListExportTransformer
     */
    public function getTransformer()
    {
        return new ApplicantShortListExportTransformer();
    }
}
