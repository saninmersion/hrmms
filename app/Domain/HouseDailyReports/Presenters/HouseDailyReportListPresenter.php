<?php

namespace App\Domain\HouseDailyReports\Presenters;

use App\Domain\HouseDailyReports\Transformers\HouseDailyReportListTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class HouseDailyReportListPresenter
 * @package App\Domain\HouseDailyReports\Presenters
 */
class HouseDailyReportListPresenter extends FractalPresenter
{

    /**
     * @return HouseDailyReportListTransformer
     */
    public function getTransformer()
    {
        return new HouseDailyReportListTransformer();
    }
}
