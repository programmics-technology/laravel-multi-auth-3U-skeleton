<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrator\SubAdmin\Auth\LoginController;
use App\Http\Controllers\Administrator\SubAdmin\HomeController;
use App\Http\Controllers\Administrator\UserController;
use App\Http\Controllers\Administrator\NotificationController;

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

//Auth Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('sub-admin');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('sub-admin.login');
Route::post('/login', [LoginController::class, 'login'])->name('sub-admin.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('sub-admin.logout')->middleware('auth:sub-admin');

$router->group(['middleware' => ['auth:sub-admin']], function($router)
{
    //Home Routes
    Route::get('/home', [HomeController::class, 'index'])->name('sub-admin.home');

    //Profile & Password Route
    Route::post('/password/update', [HomeController::class, 'password_update'])->name('sub-admin.password.update');

    //User Routes
    $router->group(['prefix' => 'users'], function($router)
    {
        Route::get('/', [UserController::class, 'index'])->name('admin.users');
        Route::get('/data', [UserController::class, 'data'])->name('admin.users.data');
        Route::post('/status', [UserController::class, 'status'])->name('admin.users.status');
    });

    //Notification Routes
    $router->group(['prefix' => 'notifications'], function($router)
    {
        Route::get('/', [NotificationController::class, 'index'])->name('admin.notifications');
        Route::get('/data', [NotificationController::class, 'data'])->name('admin.notifications.data');
        Route::post('/', [NotificationController::class, 'store'])->name('admin.notifications.store');
    });
});


