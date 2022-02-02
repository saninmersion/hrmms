<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\ApplicantListTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AdminListPresenter
 * @package App\Domain\Applicants\Presenters
 */
class ApplicantListPresenter extends FractalPresenter
{
    /**
     * @return ApplicantListTransformer
     */
    public function getTransformer()
    {
        return new ApplicantListTransformer();
    }
}
