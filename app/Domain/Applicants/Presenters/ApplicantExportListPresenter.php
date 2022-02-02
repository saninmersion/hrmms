<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\ApplicantExportListTransformer;
use League\Fractal\TransformerAbstract;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ApplicantExportListPresenter
 * @package App\Domain\Applicants\Presenters
 */
class ApplicantExportListPresenter extends FractalPresenter
{
    /**
     * @return ApplicantExportListTransformer|TransformerAbstract
     */
    public function getTransformer()
    {
        return new ApplicantExportListTransformer();
    }
}
