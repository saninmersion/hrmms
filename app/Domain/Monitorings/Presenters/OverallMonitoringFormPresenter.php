<?php


namespace App\Domain\Monitorings\Presenters;


use App\Domain\Monitorings\Transformers\OverallMonitoringFormTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class OverallMonitoringFormPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new OverallMonitoringFormTransformer();
    }
}
