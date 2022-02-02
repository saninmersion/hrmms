<?php

namespace App\Domain\Medias\Presenters;

use App\Domain\Medias\Transformers\MediaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class MediaPresenter
 * @package App\Domain\Medias\Presenters
 */
class MediaPresenter extends FractalPresenter
{
    /**
     * @return MediaTransformer
     */
    public function getTransformer()
    {
        return new MediaTransformer();
    }
}
