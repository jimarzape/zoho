<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZohoController;
use App\Http\Controllers\ZohoCreatorController;
use App\Http\Controllers\ZohoCrmController;
use App\Http\Controllers\ZohoAnalyticsController;
use App\Http\Controllers\ZohoProjectsController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {

    Route::resource('/dashboard', DashboardController::class);
    Route::resource('/', DashboardController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::resource('zoho', ZohoController::class)->only(['index','store']);
    Route::get('zoho/code', [ZohoController::class, 'code'])->name('zoho.code');

    Route::resource('creators', ZohoCreatorController::class)->only(['index','store']);
    Route::resource('crm', ZohoCrmController::class);
    Route::resource('analytics', ZohoAnalyticsController::class);
    Route::resource('projects', ZohoProjectsController::class);

    Route::prefix('/creators')->group(function () {
        Route::get('/portals', [ZohoCreatorController::class, 'getPortals'])->name('creators.portal');
        Route::get('/projects', [ZohoCreatorController::class, 'getProjects'])->name('creators.projects');
        Route::get('/tasks', [ZohoCreatorController::class, 'getTasks'])->name('creators.tasks');
    });

});



require __DIR__.'/auth.php';
