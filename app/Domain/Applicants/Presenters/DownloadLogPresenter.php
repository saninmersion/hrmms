<?php

namespace App\Domain\Applicants\Presenters;

use App\Domain\Applicants\Transformers\DownloadLogTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class DownloadLogPresenter
 * @package App\Domain\Applicants\Presenters
 */
class DownloadLogPresenter extends FractalPresenter
{
    /**
     * @return DownloadLogTransformer
     */
    public function getTransformer()
    {
        return new DownloadLogTransformer();
    }
}
