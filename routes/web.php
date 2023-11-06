<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModulesAndSubmodulesController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\RolesAndPermissionsController;
use App\Http\Controllers\UserController;
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

Route::get('reset-password/{id}/{token}', [ResetPasswordController::class, 'showResetForm']);

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {
    Route::get('/Dashboard',  [HomeController::class, 'index'])->middleware('can:Dashboard')->name('Dashboard');

    Route::prefix('/Dashboard')->group(function () {

        Route::prefix('/Users')->group(function () {

            Route::get('/Index',[UserController::class, 'index'])
            ->middleware('can:Dashboard.Users.Index')->name('Dashboard.Users.Index');

            Route::post('/Index/Query', [UserController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Users.Index.Query')->name('Dashboard.Users.Index.Query');

            Route::get('/Inactives', [UserController::class, 'inactives'])
            ->middleware('can:Dashboard.Users.Inactives')->name('Dashboard.Users.Inactives');

            Route::post('/Inactives/Query', [UserController::class, 'inactivesQuery'])
            ->middleware('can:Dashboard.Users.Inactives.Query')->name('Dashboard.Users.Inactives.Query');

            Route::post('/Create', [UserController::class, 'create'])
            ->middleware('can:Dashboard.Users.Create')->name('Dashboard.Users.Create');

            Route::post('/Store', [UserController::class, 'store'])
            ->middleware('can:Dashboard.Users.Store')->name('Dashboard.Users.Store');

            Route::post('/Edit/{id}', [UserController::class, 'edit'])
            ->middleware('can:Dashboard.Users.Edit')->name('Dashboard.Users.Edit');

            Route::put('/Update/{id}', [UserController::class, 'update'])
            ->middleware('can:Dashboard.Users.Update')->name('Dashboard.Users.Update');

            Route::post('/Show/{id}', [UserController::class, 'show'])
            ->middleware('can:Dashboard.Users.Show')->name('Dashboard.Users.Show');

            Route::put('/Password/{id}', [UserController::class, 'password'])
            ->middleware('can:Dashboard.Users.Password')->name('Dashboard.Users.Password');

            Route::delete('/Delete', [UserController::class, 'delete'])
            ->middleware('can:Dashboard.Users.Delete')->name('Dashboard.Users.Delete');

            Route::put('/Restore', [UserController::class, 'restore'])
            ->middleware('can:Dashboard.Users.Restore')->name('Dashboard.Users.Restore');

            Route::post('/AssignRoleAndPermissions',  [UserController::class, 'assignRoleAndPermissions'])
            ->middleware('can:Dashboard.Users.AssignRoleAndPermissions')->name('Dashboard.Users.AssignRoleAndPermissions');

            Route::post('/AssignRoleAndPermissions/Query',  [UserController::class, 'assignRoleAndPermissionsQuery'])
            ->middleware('can:Dashboard.Users.AssignRoleAndPermissions.Query')->name('Dashboard.Users.AssignRoleAndPermissions.Query');

            Route::post('/RemoveRoleAndPermissions',  [UserController::class, 'removeRoleAndPermissions'])
            ->middleware('can:Dashboard.Users.RemoveRoleAndPermissions')->name('Dashboard.Users.RemoveRoleAndPermissions');

            Route::post('/RemoveRoleAndPermissions/Query',  [UserController::class, 'removeRoleAndPermissionsQuery'])
            ->middleware('can:Dashboard.Users.RemoveRoleAndPermissions.Query')->name('Dashboard.Users.RemoveRoleAndPermissions.Query');

        });

        Route::prefix('/RolesAndPermissions')->group(function () {

            Route::get('/Index', [RolesAndPermissionsController::class, 'index'])
            ->middleware('can:Dashboard.RolesAndPermissions.Index')->name('Dashboard.RolesAndPermissions.Index');

            Route::post('/Index/Query', [RolesAndPermissionsController::class, 'indexQuery'])
            ->middleware('can:Dashboard.RolesAndPermissions.Index.Query')->name('Dashboard.RolesAndPermissions.Index.Query');

            Route::post('/Create', [RolesAndPermissionsController::class, 'create'])
            ->middleware('can:Dashboard.RolesAndPermissions.Create')->name('Dashboard.RolesAndPermissions.Create');

            Route::post('/Store', [RolesAndPermissionsController::class, 'store'])
            ->middleware('can:Dashboard.RolesAndPermissions.Store')->name('Dashboard.RolesAndPermissions.Store');

            Route::post('/Edit/{id}', [RolesAndPermissionsController::class, 'edit'])
            ->middleware('can:Dashboard.RolesAndPermissions.Edit')->name('Dashboard.RolesAndPermissions.Edit');

            Route::put('/Update/{id}', [RolesAndPermissionsController::class, 'update'])
            ->middleware('can:Dashboard.RolesAndPermissions.Update')->name('Dashboard.RolesAndPermissions.Update');

            Route::delete('/Delete', [RolesAndPermissionsController::class, 'delete'])
            ->middleware('can:Dashboard.RolesAndPermissions.Delete')->name('Dashboard.RolesAndPermissions.Delete');
        });

        Route::prefix('/ModulesAndSubmodules')->group(function () {

            Route::get('/Index', [ModulesAndSubmodulesController::class, 'index'])
            ->middleware('can:Dashboard.ModulesAndSubmodules.Index')->name('Dashboard.ModulesAndSubmodules.Index');

            Route::post('/Index/Query', [ModulesAndSubmodulesController::class, 'indexQuery'])
            ->middleware('can:Dashboard.ModulesAndSubmodules.Index.Query')->name('Dashboard.ModulesAndSubmodules.Index.Query');

            Route::post('/Create', [ModulesAndSubmodulesController::class, 'create'])
            ->middleware('can:Dashboard.ModulesAndSubmodules.Create')->name('Dashboard.ModulesAndSubmodules.Create');

            Route::post('/Store', [ModulesAndSubmodulesController::class, 'store'])
            ->middleware('can:Dashboard.ModulesAndSubmodules.Store')->name('Dashboard.ModulesAndSubmodules.Store');

            Route::post('/Edit/{id}', [ModulesAndSubmodulesController::class, 'edit'])
            ->middleware('can:Dashboard.ModulesAndSubmodules.Edit')->name('Dashboard.ModulesAndSubmodules.Edit');

            Route::put('/Update/{id}', [ModulesAndSubmodulesController::class, 'update'])
            ->middleware('can:Dashboard.ModulesAndSubmodules.Update')->name('Dashboard.ModulesAndSubmodules.Update');

            Route::delete('/Delete', [ModulesAndSubmodulesController::class, 'delete'])
            ->middleware('can:Dashboard.ModulesAndSubmodules.Delete')->name('Dashboard.ModulesAndSubmodules.Delete');

        });

        Route::prefix('/Collections')->group(function () {

            Route::get('/Index', [CollectionController::class, 'index'])
            ->middleware('can:Dashboard.Collections.Index')->name('Dashboard.Collections.Index');

            Route::post('/Index/Query', [CollectionController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Collections.Index.Query')->name('Dashboard.Collections.Index.Query');

            Route::post('/Create', [CollectionController::class, 'create'])
            ->middleware('can:Dashboard.Collections.Create')->name('Dashboard.Collections.Create');

            Route::post('/Store', [CollectionController::class, 'store'])
            ->middleware('can:Dashboard.Collections.Store')->name('Dashboard.Collections.Store');

            Route::post('/Edit/{id}', [CollectionController::class, 'edit'])
            ->middleware('can:Dashboard.Collections.Edit')->name('Dashboard.Collections.Edit');

            Route::put('/Update/{id}', [CollectionController::class, 'update'])
            ->middleware('can:Dashboard.Collections.Update')->name('Dashboard.Collections.Update');

            Route::delete('/Delete', [CollectionController::class, 'delete'])
            ->middleware('can:Dashboard.Collections.Delete')->name('Dashboard.Collections.Delete');

        });

        Route::prefix('/Packages')->group(function () {

            Route::get('/Index', [PackageController::class, 'index'])
            ->middleware('can:Dashboard.Packages.Index')->name('Dashboard.Packages.Index');

            Route::post('/Index/Query', [PackageController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Packages.Index.Query')->name('Dashboard.Packages.Index.Query');

            Route::post('/Create', [PackageController::class, 'create'])
            ->middleware('can:Dashboard.Packages.Create')->name('Dashboard.Packages.Create');

            Route::post('/Store', [PackageController::class, 'store'])
            ->middleware('can:Dashboard.Packages.Store')->name('Dashboard.Packages.Store');

            Route::post('/Edit/{id}', [PackageController::class, 'edit'])
            ->middleware('can:Dashboard.Packages.Edit')->name('Dashboard.Packages.Edit');

            Route::put('/Update/{id}', [PackageController::class, 'update'])
            ->middleware('can:Dashboard.Packages.Update')->name('Dashboard.Packages.Update');

            Route::delete('/Delete', [PackageController::class, 'delete'])
            ->middleware('can:Dashboard.Packages.Delete')->name('Dashboard.Packages.Delete');

            Route::put('/Restore', [PackageController::class, 'restore'])
            ->middleware('can:Dashboard.Packages.Restore')->name('Dashboard.Packages.Restore');
        });
    });
});
