<?php


namespace App\Application\Admin\Controllers;


use App\Application\Admin\Notifications\ExportStartedNotification;
use App\Domain\HouseDailyReports\Actions\ExportHouseDailyReportDailyListing;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Support\Facades\Notification;

/**
 * Class HouseDailyReportExportController
 * @package App\Application\Admin\Controllers
 */
class HouseDailyReportExportController extends AdminController
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dailyListing(): \Illuminate\Http\RedirectResponse
    {
        $exportDetail = [
            'type' => 'House Report Daily Listing',
        ];

        Notification::send([auth()->user()], new ExportStartedNotification(AuthHelper::currentUser(), $exportDetail));
        ExportHouseDailyReportDailyListing::dispatch()->onQueue('export');

        return redirect()->back();
    }
}
