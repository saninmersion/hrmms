<?php


namespace App\Domain\Applicants\Presenters;


use App\Domain\Applicants\Transformers\ShortListedApplicantDetailTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class ShortListedApplicantDetailPresenter extends FractalPresenter
{
    /**
     * @return ShortListedApplicantDetailTransformer
     */
    public function getTransformer()
    {
        return new ShortListedApplicantDetailTransformer();
    }
}
