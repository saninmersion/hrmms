<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\FormPreviewTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class FormPreviewPresenter
 * @package App\Domain\Applicants\Presenters
 */
class FormPreviewPresenter extends FractalPresenter
{
    /**
     * @inheritDoc
     */
    public function getTransformer()
    {
        return new FormPreviewTransformer();
    }
}
