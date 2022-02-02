<?php

namespace App\Domain\DailyReports\Presenters;

use App\Domain\DailyReports\Transformers\DailyReportListTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class DailyReportListPresenter
 * @package App\Domain\DailyReports\Presenters
 */
class DailyReportListPresenter extends FractalPresenter
{

    /**
     * @return DailyReportListTransformer
     */
    public function getTransformer()
    {
        return new DailyReportListTransformer();
    }
}
