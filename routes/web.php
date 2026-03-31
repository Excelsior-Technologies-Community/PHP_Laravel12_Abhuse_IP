<?php

use App\Http\Controllers\IpCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IpCheckController::class, 'index']);
Route::post('/check-ip', [IpCheckController::class, 'check'])->name('check.ip');

use App\Http\Controllers\Admin\IpControlController;

Route::prefix('admin')->group(function () {
    Route::get('/ips', [IpControlController::class, 'index'])->name('admin.ips.index');
    Route::post('/ips', [IpControlController::class, 'store'])->name('admin.ips.store');
    Route::delete('/ips/{id}', [IpControlController::class, 'destroy'])->name('admin.ips.destroy');
});