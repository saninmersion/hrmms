<?php

use App\Application\Front\Controllers\ApplicationApplyController;
use App\Application\Front\Controllers\ApplicationController;
use App\Application\Front\Controllers\ApplicationPreviewController;
use App\Application\Front\Controllers\HomeController;
use App\Application\Front\Controllers\LoginController;
use App\Application\Front\Controllers\StaticPageController;
use App\Application\Utils\Controllers\MediaController;
use App\Infrastructure\Constants\Guard;
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

$guard = Guard::APPLICANT;

Route::get('/how-to', [StaticPageController::class, 'howTo'])->name('how-to');
Route::get('/privacy-policy', [StaticPageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-conditions', [StaticPageController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('/results/enumerator', [StaticPageController::class, 'viewEnumeratorShortlist'])->name('results.enumerator');
Route::get('/results/supervisor', [StaticPageController::class, 'viewSupervisorShortlist'])->name('results.supervisor');

Route::middleware("guest:{$guard}")->group(function () {
    Route::get('/', HomeController::class)->name('home');
    Route::post('applicant/check', [ApplicationController::class, 'checkStatus'])->name('applicant.check');

    Route::prefix('applicant/login')->as('applicant.login.')->group(function () {
        Route::post('/new', [LoginController::class, 'newLogin'])->name('new');
        Route::post('/old/{type}', [LoginController::class, 'oldLogin'])->where('type', ('edit|check'))->name(
            'old'
        );
    });
});

Route::prefix('/application')->as('application.')->middleware("auth:{$guard}")->group(function () use ($guard) {
    Route::post('files/upload', [MediaController::class, 'frontUpload'])->name('files.upload');

    Route::get('/form', [ApplicationController::class, 'showForm'])->name('form');
    Route::post('/form', [ApplicationController::class, 'saveForm'])->name('form.submit');
    Route::post('/form/apply', ApplicationApplyController::class)->name('form.apply');
    Route::get('/preview', ApplicationPreviewController::class)->name('form.preview');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
