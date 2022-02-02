<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\FrontFormTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ApplicantFrontFormPresenter
 * @package App\Domain\Applicants\Presenters
 */
class FrontFormPresenter extends FractalPresenter
{
    /**
     * @return FrontFormTransformer
     */
    public function getTransformer()
    {
        return new FrontFormTransformer();
    }
}
