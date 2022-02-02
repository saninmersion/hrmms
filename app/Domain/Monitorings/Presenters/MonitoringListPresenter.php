<?php


namespace App\Domain\Monitorings\Presenters;


use App\Domain\Monitorings\Transformers\MonitoringListTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class MonitoringListPresenter extends FractalPresenter
{

    /**
     * @return MonitoringListTransformer
     */
    public function getTransformer()
    {
        return new MonitoringListTransformer();
    }
}
