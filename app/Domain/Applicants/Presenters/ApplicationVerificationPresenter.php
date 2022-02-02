<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\ApplicationVerificationTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ApplicationVerificationPresenter
 * @package App\Domain\Applicants\Presenters
 */
class ApplicationVerificationPresenter extends FractalPresenter
{
    /**
     * @return ApplicationVerificationTransformer
     */
    public function getTransformer()
    {
        return new ApplicationVerificationTransformer();
    }
}
