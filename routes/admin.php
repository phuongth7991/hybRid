<?php

use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UserController;

Route::group(['middleware' => 'admin'], static function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::group(['middleware' => 'admin.auth'], static function () {
        Route::resource('user', UserController::class);
        Route::resource('config', ConfigController::class);
    });
});
