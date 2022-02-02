<?php

use App\Application\Api\Controllers\AuthController;
use App\Application\Api\Controllers\DailyReportsBatchController;
use App\Application\Api\Controllers\DailyReportsController;
use App\Application\Api\Controllers\EnumeratorApiController;
use App\Application\Api\Controllers\HouseDailyReportsController;
use App\Application\Api\Controllers\MonitoringApiController;
use App\Application\Api\Controllers\UserProfileApiController;
use App\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/token', [AuthController::class, 'token']);


Route::middleware(['auth:sanctum'])->group(
    function () {
        Route::prefix('me')->as('me.')->group(
            function () {
                Route::get('/', [UserProfileApiController::class, 'show']);
                Route::post('/', [UserProfileApiController::class, 'update']);
            }
        );

        Route::prefix('monitoring')->as('monitoring.')->group(
            function () {
                Route::get('/form/enumerator', [MonitoringApiController::class, 'index']);
                Route::post('/form/enumerator', [MonitoringApiController::class, 'storeEnumeratorMonitoring']);
                Route::patch('/form/enumerator/{monitoring_id}', [MonitoringApiController::class, 'updateEnumeratorMonitoring']);
                Route::delete('/{monitoring_form_id}', [MonitoringApiController::class, 'destroy']);
            }
        );

        Route::prefix('daily-reports')->as('daily-reports.')->group(
            function () {
                Route::get('/', [DailyReportsController::class, 'index']);
                Route::post('/', [DailyReportsBatchController::class, 'store']);
                Route::patch('/', [DailyReportsBatchController::class, 'update']);
                Route::delete('/{report_id}', [DailyReportsController::class, 'destroy']);
            }
        );

        Route::prefix('house-daily-reports')->as('house-daily-reports.')->group(
            function () {
                Route::get('/', [HouseDailyReportsController::class, 'index']);
                Route::post('/', [HouseDailyReportsController::class, 'store']);
                Route::patch('/{report_id}', [HouseDailyReportsController::class, 'update']);
                Route::delete('/{report_id}', [HouseDailyReportsController::class, 'destroy']);
            }
        );

        Route::prefix('enumerators')->as('enumerators.')->group(
            function () {
                Route::get('/', [EnumeratorApiController::class, 'index']);
                Route::post('/', [EnumeratorApiController::class, 'store']);
                Route::patch('/{enumerator_id}', [EnumeratorApiController::class, 'update']);
            }
        );
    }
);
