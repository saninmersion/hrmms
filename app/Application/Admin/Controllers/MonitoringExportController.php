<?php

namespace App\Application\Admin\Controllers;

use App\Application\Admin\Notifications\ExportStartedNotification;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Monitorings\Actions\ExportEnumeratorMonitoring;
use App\Domain\Monitorings\Actions\ExportOverallMonitoring;
use App\Domain\Monitorings\Actions\ExportSupervisorMonitoring;
use App\Domain\Monitorings\Repositories\MonitoringRepository;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\ExportController;
use Illuminate\Support\Facades\Notification;

/**
 * Class MonitoringExportController
 * @package App\Application\Admin\Controllers
 */
class MonitoringExportController extends ExportController
{
    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;
    /**
     * @var MonitoringRepository
     */
    protected MonitoringRepository $monitoringRepository;

    public function __construct(DistrictRepository $districtRepository, UserRepository $userRepository, MonitoringRepository $monitoringRepository)
    {
        $this->districtRepository   = $districtRepository;
        $this->userRepository       = $userRepository;
        $this->monitoringRepository = $monitoringRepository;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function overall()
    {
        $exportDetail = [
            'type' => 'overall monitoring',
        ];

        Notification::send([auth()->user()], new ExportStartedNotification(AuthHelper::currentUser(), $exportDetail));
        ExportOverallMonitoring::dispatch();

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function supervisor()
    {
        $exportDetail = [
            'type' => 'supervisor monitoring',
        ];

        Notification::send([auth()->user()], new ExportStartedNotification(AuthHelper::currentUser(), $exportDetail));
        ExportSupervisorMonitoring::dispatch();

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enumerator()
    {
        $exportDetail = [
            'type' => 'enumerator monitoring',
        ];

        Notification::send([auth()->user()], new ExportStartedNotification(AuthHelper::currentUser(), $exportDetail));
        ExportEnumeratorMonitoring::dispatch();

        return redirect()->back();
    }
}
