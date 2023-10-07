<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('Dashboard');
    } else {
        return redirect('/login');
    }

});

Auth::routes(['register' => false]);



Route::middleware(['auth'])->group(function () {
    Route::get('/Dashboard', [App\Http\Controllers\HomeController::class, 'index'])->middleware('can:Dashboard')->name('Dashboard');

    Route::prefix('Dashboard')->group(function () { 

        Route::post('Users/Index', [\App\Http\Controllers\UserController::class, 'index'])->middleware('can:Dashboard.Users.Index')->name('Dashboard.Users.Index');
        Route::post('Users/Inactives', [\App\Http\Controllers\UserController::class, 'inactives'])->middleware('can:Dashboard.Users.Inactives')->name('Dashboard.Users.Inactives');
        Route::post('Users/Store', [\App\Http\Controllers\UserController::class, 'store'])->middleware('can:Dashboard.Users.Store')->name('Dashboard.Users.Store');
        Route::put('Users/Update/{id}', [\App\Http\Controllers\UserController::class, 'update'])->middleware('can:Dashboard.Users.Update')->name('Dashboard.Users.Update');
        Route::delete('Users/Delete', [\App\Http\Controllers\UserController::class, 'delete'])->middleware('can:Dashboard.Users.Delete')->name('Dashboard.Users.Delete');
        Route::put('Users/Restore', [\App\Http\Controllers\UserController::class, 'restore'])->middleware('can:Dashboard.Users.Restore')->name('Dashboard.Users.Restore');
        Route::post('Users/AssignRoleAndPermissions',  [\App\Http\Controllers\UserController::class, 'assignRoleAndPermissions'])->middleware('can:Dashboard.Users.AssignRoleAndPermissions')->name('Dashboard.Users.AssignRoleAndPermissions');
        Route::post('Users/RemoveRoleAndPermissions',  [\App\Http\Controllers\UserController::class, 'removeRoleAndPermissions'])->middleware('can:Dashboard.Users.RemoveRoleAndPermissions')->name('Dashboard.Users.RemoveRoleAndPermissions');
        
        Route::post('RolesAndPermissions/Index', [\App\Http\Controllers\RolesAndPermissionsController::class, 'index'])->middleware('can:Dashboard.RolesAndPermissions.Index')->name('Dashboard.RolesAndPermissions.Index');
        Route::post('RolesAndPermissions/Store', [\App\Http\Controllers\RolesAndPermissionsController::class, 'store'])->middleware('can:Dashboard.RolesAndPermissions.Store')->name('Dashboard.RolesAndPermissions.Store');
        Route::put('RolesAndPermissions/Update/{id}', [\App\Http\Controllers\RolesAndPermissionsController::class, 'update'])->middleware('can:Dashboard.RolesAndPermissions.Update')->name('Dashboard.RolesAndPermissions.Update');
        Route::delete('RolesAndPermissions/Delete', [\App\Http\Controllers\RolesAndPermissionsController::class, 'delete'])->middleware('can:Dashboard.RolesAndPermissions.Delete')->name('Dashboard.RolesAndPermissions.Delete');
    });
});
