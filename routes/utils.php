<?php

use App\Application\Utils\Controllers\FilesController;
use App\Application\Utils\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('media/{disk}/{path}', FilesController::class)->where('path', '.*')->name('medias');
Route::post('locale', LocaleController::class)->name('locale-set');
