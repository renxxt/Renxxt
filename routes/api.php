<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PickupFormController;
use App\Http\Controllers\ReturnFormController;
use App\Http\Controllers\ApplicationFormController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserManagementController::class)->group(function () {
    Route::post('/getUser', 'getUser')->name('api.getUser');
});

Route::controller(QuestionController::class)->group(function () {
    Route::get('/getQuestion', 'getQuestion')->name('api.getQuestion');
});

Route::controller(DeviceController::class)->group(function () {
    Route::get('/device/list', 'list')->name('api.device.list');
    Route::post('/getDevice', 'getDevice')->name('api.getDevice');
});

Route::controller(ApplicationFormController::class)->group(function () {
    Route::post('/list', 'list')->name('api.list');
    Route::post('/detail', 'detail')->name('api.detail');
    Route::post('/updateReturnTime', 'updateReturnTime')->name('api.updateReturnTime');
});

Route::controller(PickupFormController::class)->group(function () {
    Route::post('/pickupForm/list', 'list')->name('api.pickupForm.list');
    Route::post('/pickupFormAnswer/list', 'answerList')->name('api.pickupFormAnswer.list');
});

Route::controller(ReturnFormController::class)->group(function () {
    Route::post('/returnForm/list', 'list')->name('api.returnForm.list');
    Route::post('/returnFormAnswer/list', 'answerList')->name('api.returnFormAnswer.list');
});

