<?php


namespace App\Domain\DailyReports\Presenters;


use App\Domain\DailyReports\Transformers\DailyReportApiTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class DailyReportApiPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new DailyReportApiTransformer();
    }
}
