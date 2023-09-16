<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;

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
