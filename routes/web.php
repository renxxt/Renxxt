<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DeviceAttributeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StagedApplicationFormController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\ApplicationManagementController;
use App\Http\Controllers\PickupFormController;
use App\Http\Controllers\ReturnFormController;

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
Route::controller(UserController::class)->group(function () {
    Route::match(['get','post'], 'login', 'login')->name('login');
    Route::get('/logout','logout')->name('logout');
    Route::get('/forgetPwd','forgetPwd')->name('forgetPwd');
    Route::post('/emailVerify', 'emailVerify')->name('emailVerify');
    Route::get('/verify/{id}/{hash}', 'verify')->name('verify');
    Route::post('/resetPwd', 'resetPwd')->name('resetPwd');
    Route::middleware('auth')->group(function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::put('/profile', 'update')->name('profile.update');
    });
});

Route::controller(UserManagementController::class)->middleware('auth')->group(function () {
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

Route::controller(DepartmentController::class)->middleware('auth')->group(function () {
    Route::prefix('departmentManagement')->group(function () {
        Route::get('/list', 'list')->name('departmentManagement.list');
        Route::post('/department', 'store')->name('departmentManagement.store');
        Route::put('/department', 'update')->name('departmentManagement.update');
        Route::delete('/department', 'delete')->name('departmentManagement.delete');
    });
});

Route::controller(PositionController::class)->middleware('auth')->group(function () {
    Route::prefix('positionManagement')->group(function () {
        Route::get('/list', 'list')->name('positionManagement.list');
        Route::post('/position', 'store')->name('positionManagement.store');
        Route::post('/changeOrder', 'changeOrder')->name('positionManagement.changeOrder');
    });
});

Route::controller(DeviceAttributeController::class)->middleware('auth')->group(function () {
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

Route::controller(DeviceController::class)->middleware('auth')->group(function () {
    Route::prefix('serviceManagement')->group(function () {
        Route::get('/device', 'create')->name('serviceManagement.device.create');
        Route::post('/device', 'store')->name('serviceManagement.device.store');
        Route::get('/device/{id}', 'show')->name('serviceManagement.device.show');
        Route::put('/device', 'update')->name('serviceManagement.device.update');
        Route::delete('/device', 'delete')->name('serviceManagement.device.delete');
        Route::post('/device/changeDisplay', 'changeDisplay')->name('serviceManagement.device.changeDisplay');
    });
});

Route::controller(QuestionController::class)->middleware('auth')->group(function () {
    Route::prefix('questionManagement')->group(function () {
        Route::get('/question', 'list')->name('questionManagement.list');
        Route::post('/question', 'store')->name('questionManagement.store');
        Route::put('/question', 'update')->name('questionManagement.update');
        Route::delete('/question', 'delete')->name('questionManagement.delete');
    });
});

Route::controller(StagedApplicationFormController::class)->middleware('auth')->group(function () {
    Route::prefix('staged')->group(function () {
        Route::get('/applicationForm/list', 'list')->name('staged.list');
        Route::post('/applicationForm', 'store')->name('staged.store');
        Route::post('/applicationForm/show', 'show')->name('staged.show');
        Route::put('/applicationForm', 'update')->name('staged.update');
        Route::delete('/applicationForm', 'delete')->name('staged.delete');
    });
});

Route::controller(ApplicationFormController::class)->middleware('auth')->group(function () {
    Route::get('/renxxt', 'index')->name('renxxt');
    Route::get('/applicationForm/list', 'applicationList')->name('applicationForm.applicationList');
    Route::get('/applicationForm/cancelList', 'cancelList')->name('applicationForm.cancelList');
    Route::get('/applicationForm/completedList', 'completedList')->name('applicationForm.completedList');
    Route::get('/applicationForm', 'create')->name('applicationForm.create');
    Route::post('/applicationForm', 'store')->name('applicationForm.store');
    Route::get('/applicationForm/{id}', 'show')->name('applicationForm.show');
    Route::put('/applicationForm', 'update')->name('applicationForm.update');
    Route::post('/applicationForm/cancel', 'cancel')->name('applicationForm.cancel');
});

Route::controller(ApplicationManagementController::class)->middleware('auth')->group(function () {
    Route::prefix('applicationManagement')->group(function () {
        Route::get('/list', 'applicationList')->name('applicationManagement.applicationList');
        Route::get('/cancelList', 'cancelList')->name('applicationManagement.cancelList');
        Route::get('/completedList', 'completedList')->name('applicationManagement.completedList');
        Route::post('/approve', 'approve')->name('applicationManagement.approve');
    });
});

Route::controller(PickupFormController::class)->middleware('auth')->group(function () {
    Route::get('/pickupForm/{id}', 'show')->name('pickupForm.show');
    Route::post('/pickupForm', 'store')->name('pickupForm.store');
});

Route::controller(ReturnFormController::class)->middleware('auth')->group(function () {
    Route::get('/returnForm/{id}', 'show')->name('returnForm.show');
    Route::post('/returnForm', 'store')->name('returnForm.store');
});
