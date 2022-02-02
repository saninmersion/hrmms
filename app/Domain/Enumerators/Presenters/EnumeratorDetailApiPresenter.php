<?php


namespace App\Domain\Enumerators\Presenters;


use App\Domain\Enumerators\Transformers\EnumeratorDetailApiTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class EnumeratorDetailApiPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new EnumeratorDetailApiTransformer();
    }
}
