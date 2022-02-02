<?php


namespace App\Domain\HouseDailyReports\Presenters;


use App\Domain\HouseDailyReports\Transformers\HouseDailyReportApiTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class HouseDailyReportApiPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new HouseDailyReportApiTransformer();
    }
}
