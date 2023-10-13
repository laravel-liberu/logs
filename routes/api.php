<?php

use Illuminate\Support\Facades\Route;
use LaravelLiberu\Logs\Http\Controllers\Destroy;
use LaravelLiberu\Logs\Http\Controllers\Download;
use LaravelLiberu\Logs\Http\Controllers\Index;
use LaravelLiberu\Logs\Http\Controllers\Show;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/system/logs')
    ->as('system.logs.')
    ->group(function () {
        Route::get('', Index::class)->name('index');
        Route::delete('{log}', Destroy::class)->name('destroy');
        Route::get('{log}/download', Download::class)->name('download');
        Route::get('{log}', Show::class)->name('show');
    });
