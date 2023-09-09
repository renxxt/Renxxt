<?php
use App\Http\Controllers\ManagementController;
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

# controller
Route::controller(ManagementController::class)->group(function () {
    Route::get('/header', 'header')->name('header');
    Route::match(['get', 'post'], '/userManagement/userList', 'userList')->name('userManagement.userList');
    Route::match(['get', 'post'], '/userManagement/createUser', 'createUser')->name('userManagement.createUser');
    Route::get('/userManagement/editUser', 'editUser')->name('userManagement.editUser');
    Route::post('/userManagement/updateUser', 'updateUser')->name('userManagement.updateUser');
    Route::post('/userManagement/deleteUser', 'deleteUser')->name('userManagement.deleteUser');
});
