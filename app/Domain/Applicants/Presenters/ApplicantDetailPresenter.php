<?php


namespace App\Domain\Applicants\Presenters;


use App\Domain\Applicants\Transformers\ApplicantDetailTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class ApplicantDetailPresenter extends FractalPresenter
{

    /**
     * @inheritDoc
     */
    public function getTransformer()
    {
        return new ApplicantDetailTransformer();
    }
}
