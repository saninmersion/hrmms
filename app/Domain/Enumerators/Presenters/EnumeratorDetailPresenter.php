<?php


namespace App\Domain\Enumerators\Presenters;


use App\Domain\Enumerators\Transformers\EnumeratorDetailTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class EnumeratorDetailPresenter extends FractalPresenter
{
    /**
     * @return \App\Domain\Enumerators\Transformers\EnumeratorDetailTransformer
     */
    public function getTransformer(): EnumeratorDetailTransformer
    {
        return new EnumeratorDetailTransformer();
    }
}
