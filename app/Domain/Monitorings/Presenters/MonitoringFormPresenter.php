<?php


namespace App\Domain\Monitorings\Presenters;


use App\Domain\Monitorings\Transformers\MonitoringFormTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class MonitoringFormPresenter extends FractalPresenter
{

    public function getTransformer()
    {
        // TODO: Implement getTransformer() method.
        return new MonitoringFormTransformer();
    }
}
