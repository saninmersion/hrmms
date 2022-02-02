<?php


namespace App\Domain\Enumerators\Presenters;


use App\Domain\Enumerators\Transformers\EnumeratorApiTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class EnumeratorApiPresenter extends FractalPresenter
{

    public function getTransformer()
    {
        return new EnumeratorApiTransformer();
    }
}
