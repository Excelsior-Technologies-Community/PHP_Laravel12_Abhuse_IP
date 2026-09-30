<?php

use App\Http\Controllers\IpCheckController;
use App\Http\Controllers\Admin\IpControlController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| IP Abuse Checker
|--------------------------------------------------------------------------
*/

Route::get('/', [IpCheckController::class, 'index']);

Route::post('/check-ip', [
    IpCheckController::class,
    'check'
])->name('check.ip');


/*
|--------------------------------------------------------------------------
| Admin IP Protection
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::get('/ips', [
        IpControlController::class,
        'index'
    ])->name('admin.ips.index');

    Route::post('/ips', [
        IpControlController::class,
        'store'
    ])->name('admin.ips.store');

    Route::delete('/ips/{id}', [
        IpControlController::class,
        'destroy'
    ])->name('admin.ips.destroy');

});