<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrator\Admin\Auth\LoginController;
use App\Http\Controllers\Administrator\Admin\HomeController;
use App\Http\Controllers\Administrator\Admin\SettingController;
use App\Http\Controllers\Administrator\Admin\SubAdminController;

//Common Administrator Controllers.
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
Route::get('/', [LoginController::class, 'showLoginForm'])->name('admin');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [LoginController::class, 'login'])->name('admin.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout')->middleware('auth:admin');

$router->group(['middleware' => ['auth:admin']], function($router)
{
    //Home Routes
    Route::get('/home', [HomeController::class, 'index'])->name('admin.home');

    //Profile & Password Routes
    Route::post('/password/update', [HomeController::class, 'password_update'])->name('admin.password.update');

    //User Routes
    $router->group(['prefix' => 'users'], function($router)
    {
        Route::get('/', [UserController::class, 'index'])->name('admin.users');
        Route::get('/data', [UserController::class, 'data'])->name('admin.users.data');
        Route::post('/status', [UserController::class, 'status'])->name('admin.users.status');
    });

    //User Routes
    $router->group(['prefix' => 'sub-admins'], function($router)
    {
        Route::get('/', [SubAdminController::class, 'index'])->name('admin.sub-admins');
        Route::post('/', [SubAdminController::class, 'store'])->name('admin.sub-admins.create');
        Route::get('/data', [SubAdminController::class, 'data'])->name('admin.sub-admins.data');
        Route::post('/status', [SubAdminController::class, 'status'])->name('admin.sub-admins.status');
        Route::post('/update', [SubAdminController::class, 'update'])->name('admin.sub-admins.update');
    });

    //Notification Routes
    $router->group(['prefix' => 'notifications'], function($router)
    {
        Route::get('/', [NotificationController::class, 'index'])->name('admin.notifications');
        Route::get('/data', [NotificationController::class, 'data'])->name('admin.notifications.data');
        Route::post('/', [NotificationController::class, 'store'])->name('admin.notifications.store');
    });

    //Setting Routes
    $router->group(['prefix' => 'settings'], function($router)
    {
        Route::get('/', [SettingController::class, 'index'])->name('admin.settings');
        Route::get('/data', [SettingController::class, 'data'])->name('admin.settings.data');
        Route::post('/update', [SettingController::class, 'update'])->name('admin.settings.update');
    });

}); //Group Route Close Tag.
