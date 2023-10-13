<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

Route::get('reset-password/{id}/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm']);

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {
    Route::get('/Dashboard', [App\Http\Controllers\HomeController::class, 'index'])->middleware('can:Dashboard')->name('Dashboard');

    Route::prefix('/Dashboard')->group(function () {

        Route::prefix('/Users')->group(function () {

            Route::get('/Index',[\App\Http\Controllers\UserController::class, 'index'])
            ->middleware('can:Dashboard.Users.Index')->name('Dashboard.Users.Index');

            Route::post('/Index/Query', [\App\Http\Controllers\UserController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Users.Index.Query')->name('Dashboard.Users.Index.Query');

            Route::get('/Inactives', [\App\Http\Controllers\UserController::class, 'inactives'])
            ->middleware('can:Dashboard.Users.Inactives')->name('Dashboard.Users.Inactives');

            Route::post('/Inactives/Query', [\App\Http\Controllers\UserController::class, 'inactivesQuery'])
            ->middleware('can:Dashboard.Users.Inactives.Query')->name('Dashboard.Users.Inactives.Query');

            Route::post('/Store', [\App\Http\Controllers\UserController::class, 'store'])
            ->middleware('can:Dashboard.Users.Store')->name('Dashboard.Users.Store');

            Route::put('/Update/{id}', [\App\Http\Controllers\UserController::class, 'update'])
            ->middleware('can:Dashboard.Users.Update')->name('Dashboard.Users.Update');

            Route::put('/Password/{id}', [\App\Http\Controllers\UserController::class, 'password'])
            ->middleware('can:Dashboard.Users.Password')->name('Dashboard.Users.Password');

            Route::delete('/Delete', [\App\Http\Controllers\UserController::class, 'delete'])
            ->middleware('can:Dashboard.Users.Delete')->name('Dashboard.Users.Delete');

            Route::put('/Restore', [\App\Http\Controllers\UserController::class, 'restore'])
            ->middleware('can:Dashboard.Users.Restore')->name('Dashboard.Users.Restore');

            Route::post('/AssignRoleAndPermissions',  [\App\Http\Controllers\UserController::class, 'assignRoleAndPermissions'])
            ->middleware('can:Dashboard.Users.AssignRoleAndPermissions')->name('Dashboard.Users.AssignRoleAndPermissions');

            Route::post('/AssignRoleAndPermissions/Query',  [\App\Http\Controllers\UserController::class, 'assignRoleAndPermissionsQuery'])
            ->middleware('can:Dashboard.Users.AssignRoleAndPermissions.Query')->name('Dashboard.Users.AssignRoleAndPermissions.Query');

            Route::post('/RemoveRoleAndPermissions',  [\App\Http\Controllers\UserController::class, 'removeRoleAndPermissions'])
            ->middleware('can:Dashboard.Users.RemoveRoleAndPermissions')->name('Dashboard.Users.RemoveRoleAndPermissions');

            Route::post('/RemoveRoleAndPermissions/Query',  [\App\Http\Controllers\UserController::class, 'removeRoleAndPermissionsQuery'])
            ->middleware('can:Dashboard.Users.RemoveRoleAndPermissions.Query')->name('Dashboard.Users.RemoveRoleAndPermissions.Query');

        });

        Route::prefix('/RolesAndPermissions')->group(function () {

            Route::get('/Index', [\App\Http\Controllers\RolesAndPermissionsController::class, 'index'])
            ->middleware('can:Dashboard.RolesAndPermissions.Index')->name('Dashboard.RolesAndPermissions.Index');

            Route::post('/Index/Query', [\App\Http\Controllers\RolesAndPermissionsController::class, 'indexQuery'])
            ->middleware('can:Dashboard.RolesAndPermissions.Index.Query')->name('Dashboard.RolesAndPermissions.Index.Query');

            Route::post('/Store', [\App\Http\Controllers\RolesAndPermissionsController::class, 'store'])
            ->middleware('can:Dashboard.RolesAndPermissions.Store')->name('Dashboard.RolesAndPermissions.Store');

            Route::put('/Update/{id}', [\App\Http\Controllers\RolesAndPermissionsController::class, 'update'])
            ->middleware('can:Dashboard.RolesAndPermissions.Update')->name('Dashboard.RolesAndPermissions.Update');

            Route::delete('/Delete', [\App\Http\Controllers\RolesAndPermissionsController::class, 'delete'])
            ->middleware('can:Dashboard.RolesAndPermissions.Delete')->name('Dashboard.RolesAndPermissions.Delete');

        });

    });
});
