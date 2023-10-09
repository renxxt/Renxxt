<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DeviceAttributeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\QuestionController;

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

# controller
Route::controller(UserManagementController::class)->group(function () {
    Route::get('/header', 'header')->name('header');
    Route::prefix('userManagement')->group(function () {
        Route::get('/list', 'list')->name('userManagement.list');
        Route::get('/user', 'create')->name('userManagement.create');
        Route::post('/user', 'store')->name('userManagement.store');
        Route::get('/user/{id}', 'show')->name('userManagement.show');
        Route::put('/user', 'update')->name('userManagement.update');
        Route::delete('/user', 'delete')->name('userManagement.delete');
    });
});

Route::controller(DeviceAttributeController::class)->group(function () {
    Route::prefix('serviceManagement')->group(function () {
        Route::get('/list', 'list')->name('serviceManagement.list');
        Route::get('/attribute', 'create')->name('serviceManagement.attribute.create');
        Route::post('/attribute', 'store')->name('serviceManagement.attribute.store');
        Route::get('/attribute/{id}', 'show')->name('serviceManagement.attribute.show');
        Route::put('/attribute', 'update')->name('serviceManagement.attribute.update');
        Route::delete('/attribute', 'delete')->name('serviceManagement.attribute.delete');
        Route::post('/attribute/changeDisplay', 'changeDisplay')->name('serviceManagement.attribute.changeDisplay');
    });
});

Route::controller(DeviceController::class)->group(function () {
    Route::prefix('serviceManagement')->group(function () {
        Route::get('/device', 'create')->name('serviceManagement.device.create');
        Route::post('/device', 'store')->name('serviceManagement.device.store');
        Route::get('/device/{id}', 'show')->name('serviceManagement.device.show');
        Route::put('/device', 'update')->name('serviceManagement.device.update');
        Route::delete('/device', 'delete')->name('serviceManagement.device.delete');
        Route::post('/device/changeDisplay', 'changeDisplay')->name('serviceManagement.device.changeDisplay');
    });
});

Route::controller(QuestionController::class)->group(function () {
    Route::prefix('serviceManagement')->group(function () {
        Route::post('/question', 'store')->name('serviceManagement.question.store');
    });
});
