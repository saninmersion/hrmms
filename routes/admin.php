<?php

use App\Application\Admin\Controllers\ApplicantExportController;
use App\Application\Admin\Controllers\ApplicantsController;
use App\Application\Admin\Controllers\ApplicationOfflineFormController;
use App\Application\Admin\Controllers\AssignedApplicationController;
use App\Application\Admin\Controllers\DashboardController;
use App\Application\Admin\Controllers\DistrictDashboardController;
use App\Application\Admin\Controllers\DistrictShortListController;
use App\Application\Admin\Controllers\NotificationsController;
use App\Application\Admin\Controllers\ShortListingController;
use App\Application\Admin\Controllers\ShortListingExportController;
use App\Application\Admin\Controllers\UserPasswordChangeController;
use App\Application\Admin\Controllers\UsersController;
use App\Application\Admin\Controllers\UserStatusController;
use App\Application\Utils\Controllers\MediaController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', DashboardController::class)->name('dashboard');
Route::get('/districts', [DistrictDashboardController::class, 'index'])->name('dashboard.districts');
Route::get('/districts/{district_code}', [DistrictDashboardController::class, 'show'])->name('dashboard.districts.show');

Route::prefix('applications')->as('applications.')->group(function () {
    Route::get('/offline', [ApplicantsController::class, 'indexOffline'])->name('offline.index');
    Route::get('/', [ApplicantsController::class, 'index'])->name('index');
    Route::get('/form/check', [ApplicantsController::class, 'showApplicantCheckForm'])->name('offline-form.check.show');
    Route::post('/form/check', [ApplicantsController::class, 'checkApplicant'])->name('offline-form.check');

    Route::get('/form/check', [ApplicationOfflineFormController::class, 'showApplicantCheckForm'])->name('offline-form.check.show');
    Route::post('/form/check', [ApplicationOfflineFormController::class, 'checkApplicant'])->name('offline-form.check');
    Route::get('/form/{applicant_id}', [ApplicationOfflineFormController::class, 'showForm'])->name('offline-form.show');
    Route::post('/form/{applicant_id}', [ApplicationOfflineFormController::class, 'saveForm'])->name('offline-form.save');

    Route::get('/exports', [ApplicantExportController::class, 'index'])->name('exports.index');
    Route::get('/exports/{export_id}/{path}', [ApplicantExportController::class, 'download'])->where('path', '.*')->name('exports.download');

    Route::get('/{applicant_id}', [ApplicantsController::class, 'show'])->name('show');

    Route::post('files/upload', [MediaController::class, 'adminUpload'])->name('files.upload');
});

Route::prefix('assigned-applications')->as('assigned-applications.')->group(function () {
    Route::get('/', [AssignedApplicationController::class, 'index'])->name('index');
    Route::get('/{applicant_id}', [AssignedApplicationController::class, 'form'])->name('form');
    Route::post('/{applicant_id}', [AssignedApplicationController::class, 'verify'])->name('verify');
});

Route::prefix("/shortlisting")->as('shortlisting.')->group(function () {
    Route::get('/', [ShortListingController::class, 'index'])->name('index');
    Route::get('/shortlisted', [DistrictShortListController::class, 'index'])->name('district.index');
    Route::get('/{applicant_id}/{role}', [ShortListingController::class, 'show'])->name('show');
    Route::get('export-scored', [ShortListingExportController::class, 'exportScoredList'])->name('export.scored');
    Route::get('export-shortlist', [ShortListingExportController::class, 'exportShortlist'])->name('export.shortlist');
    Route::post('/{applicant_id}/shortlist', [ShortListingController::class, 'shortlist'])->name('applicant.shortlist');
    Route::post('/{applicant_id}/un-shortlist', [ShortListingController::class, 'unShortlist'])->name('applicant.un-shortlist');
    Route::post('/{applicant_id}/{role}/hire', [ShortListingController::class, 'changeHiringStatus'])->name('applicant.hire');
});

Route::prefix("/shortlist")->as('shortlist.')->group(function () {
    Route::get('/{applicant_id}/{role}', [DistrictShortListController::class, 'show'])->name('district.show');
});

Route::prefix('users')->as('users.')->group(function () {
    Route::get('/', [UsersController::class, 'index'])->name('index');
    Route::get('/inactive-monitors', [UsersController::class, 'indexInactiveMonitors'])->name('index.inactive-monitors');
    Route::get('/create', [UsersController::class, 'create'])->name('create');
    Route::post('/', [UsersController::class, 'store'])->name('store');
    Route::get('/{user_id}', [UsersController::class, 'show'])->name('show');
    Route::put('/{user_id}/change-password', [UserPasswordChangeController::class, 'changePassword'])->name('change-password');
    Route::get('/{user_id}/edit', [UsersController::class, 'edit'])->name('edit');
    Route::put('/{user_id}', [UsersController::class, 'update'])->name('update');
    Route::post('/{user_id}/deactivate', [UserStatusController::class, 'deactivateUser'])->name('deactivate');
    Route::post('/{user_id}/activate', [UserStatusController::class, 'activateUser'])->name('activate');
    Route::delete('/{user_id}', [UsersController::class, 'delete'])->name('destroy');
});

Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
Route::post('/notifications/mark-as-read', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');

// Artisan Commands
Route::get('/console/applications/export', function () {
    Artisan::queue('cbs:applications:export');

    return redirect()->back();
})->name('console.applications.export');

Route::get('/console/stats/compute', function () {
    Artisan::queue('cbs:compute:stats');

    return redirect()->back();
})->name('console.stats.compute');

//Route::prefix('verifications')->as('verifications.')->group(function () {
//    Route::get('/exports/rejection', [VerificationExportController::class, 'exportRejectionList'])->name('exports.rejection');
//    Route::get('/exports/correction-needed', [VerificationExportController::class, 'exportCorrectionNeededList'])->name('exports.correction_needed');
//    Route::get('/stats', [ApplicationVerificationController::class, 'stats'])->name('stats');
//    Route::get('/{verifier}', [ApplicationVerificationController::class, 'listByUser'])->name('user');
//    Route::get('/{verifier}/applications/{applicantId}', [ApplicationVerificationController::class, 'show'])->name('show');
//});

//Route::prefix('monitorings')->as('monitorings.')->group(function () {
//    Route::get('/', [MonitoringController::class, 'index'])->name('index');
//    Route::get('/dashboard', [MonitoringDashboardController::class, 'show'])->name('dashboard');
//    Route::get('/form/overall', [MonitoringController::class, 'showOverallMonitoringForm'])->name('form.overall');
//    Route::post('/form/overall', [MonitoringController::class, 'storeOverallMonitoringForm'])->name('form.overall.store');
//    Route::get('/form/supervisor', [MonitoringController::class, 'showSupervisorMonitoringForm'])->name('form.supervisor');
//    Route::post('/form/supervisor', [MonitoringController::class, 'storeSupervisorMonitoringForm'])->name('form.supervisor.store');
//    Route::get('/form/enumerator', [MonitoringController::class, 'showEnumeratorMonitoringForm'])->name('form.enumerator');
//    Route::post('/form/enumerator', [MonitoringController::class, 'storeEnumeratorMonitoringForm'])->name('form.enumerator.store');
//    Route::get('/{monitoring_id}', [MonitoringController::class, 'show'])->name('show');
//    Route::get('/{monitoring_id}/edit', [MonitoringController::class, 'edit'])->name('edit');
//    Route::post('/{monitoring_id}/overall', [MonitoringController::class, 'updateOverallMonitoringForm'])->name('overall.update');
//    Route::post('/{monitoring_id}/enumerator', [MonitoringController::class, 'updateEnumeratorMonitoringForm'])->name('enumerator.update');
//    Route::post('/{monitoring_id}/supervisor', [MonitoringController::class, 'updateSupervisorMonitoringForm'])->name('supervisor.update');
//    Route::get('/exports/overall', [MonitoringExportController::class, 'overall'])->name('exports.overall');
//    Route::get('/exports/supervisor', [MonitoringExportController::class, 'supervisor'])->name('exports.supervisor');
//    Route::get('/exports/enumerator', [MonitoringExportController::class, 'enumerator'])->name('exports.enumerator');
//});
//
//Route::prefix('/daily-reports')->as('daily-reports.')->group(function () {
//    Route::get('/dashboard', DailyReportDashboardController::class)->name('dashboard');
//    Route::get('/', [DailyReportsController::class, 'index'])->name('index');
//    Route::get('/create', [DailyReportsController::class, 'create'])->name('create');
//    Route::post('/', [DailyReportsController::class, 'store'])->name('store');
//    Route::get('/{report_id}', [DailyReportsController::class, 'edit'])->name('edit');
//    Route::post('/{report_id}', [DailyReportsController::class, 'update'])->name('update');
//    Route::delete('/{report_id}', [DailyReportsController::class, 'delete'])->name('destroy');
//});
//
//Route::prefix('/house-daily-reports')->as('house-daily-reports.')->group(function () {
//    Route::get('/dashboard', HouseDailyReportDashboardController::class)->name('dashboard');
//    Route::get('/', [HouseDailyReportsController::class, 'index'])->name('index');
//    Route::get('/create', [HouseDailyReportsController::class, 'create'])->name('create');
//    Route::post('/', [HouseDailyReportsController::class, 'store'])->name('store');
//    Route::get('/{report_id}', [HouseDailyReportsController::class, 'edit'])->name('edit');
//    Route::post('/{report_id}', [HouseDailyReportsController::class, 'update'])->name('update');
//    Route::delete('/{report_id}', [HouseDailyReportsController::class, 'delete'])->name('destroy');
//    Route::get('/exports/daily-listing', [HouseDailyReportExportController::class, 'dailyListing'])->name('exports.daily-listing');
//});
//
//Route::prefix('/enumerators')->as('enumerators.')->group(function () {
//    Route::get('/', [EnumeratorController::class, 'index'])->name('index');
//    Route::get('/create', [EnumeratorController::class, 'create'])->name('create');
//    Route::post('/', [EnumeratorController::class, 'store'])->name('store');
//    Route::get('/{enumerator_id}', [EnumeratorController::class, 'edit'])->name('edit');
//    Route::post('/{enumerator_id}', [EnumeratorController::class, 'update'])->name('update');
//});
