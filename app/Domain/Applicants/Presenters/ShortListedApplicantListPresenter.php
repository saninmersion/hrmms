<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\ShortListedApplicantListTransformer;
use League\Fractal\TransformerAbstract;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ShortListedApplicantListPresenter
 * @package App\Domain\Applicants\Presenters
 */
class ShortListedApplicantListPresenter extends FractalPresenter
{
    /**
     * @return TransformerAbstract|void
     */
    public function getTransformer()
    {
        return new ShortListedApplicantListTransformer();
    }
}
