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
    return view('Auth/login');
});

Auth::routes(['register' => false]);

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
    /* USER */
    Route::get('/Dashboard/User/Index', 'App\Http\Controllers\UserController@index')->middleware('can:Dashboard.User.Index')->name('Dashboard.User.Index');
    Route::get('/Dashboard/User/Create', 'App\Http\Controllers\UserController@create')->middleware('can:Dashboard.User.Create')->name('Dashboard.User.Create');
    Route::post('/Dashboard/User/Store', 'App\Http\Controllers\UserController@store')->middleware('can:Dashboard.User.Store')->name('Dashboard.User.Store');
    Route::post('/Dashboard/User/Password', 'App\Http\Controllers\UserController@updateuser')->middleware('can:Dashboard.User.Password')->name('Dashboard.User.Password');
    Route::get('/Dashboard/User/Edit/{id}', 'App\Http\Controllers\UserController@edit')->middleware('can:Dashboard.User.Edit')->name('Dashboard.User.Edit');
    Route::post('/Dashboard/User/Update/{id}', 'App\Http\Controllers\UserController@update')->middleware('can:Dashboard.User.Update')->name('Dashboard.User.Update');
    Route::get('/Dashboard/User/Show/Module/{id}', 'App\Http\Controllers\UserController@show_module')->middleware('can:Dashboard.User.Show.Module')->name('Dashboard.User.Show.Module');
    Route::post('Dashboard/User/Assign_module/{id}', 'App\Http\Controllers\UserController@user_assign_module')->middleware('can:Dashboard.User.Assign_module')->name('Dashboard.User.Assign_module');
    Route::get('/Dashboard/User/Hide/Module/{id}', 'App\Http\Controllers\UserController@hide_module')->middleware('can:Dashboard.User.Hide.Module')->name('Dashboard.User.Hide.Module');
    Route::post('Dashboard/User/Unssign_module/{id}', 'App\Http\Controllers\UserController@user_unssign_module')->middleware('can:Dashboard.User.Unssign_module')->name('Dashboard.User.Unssign_module');
    Route::get('/Dashboard/User/Show/SubModule/{id}', 'App\Http\Controllers\UserController@show_submodule')->middleware('can:Dashboard.User.Show.SubModule')->name('Dashboard.User.Show.SubModule');
    Route::post('/Dashboard/User/Show/SubModule/allsubmodule', 'App\Http\Controllers\UserController@show_allsubmodule')->middleware('can:Dashboard.User.Show.SubModule.allsubmodule')->name('Dashboard.User.Show.SubModule.allsubmodule');
    Route::post('Dashboard/User/Assign_submodule/{id}', 'App\Http\Controllers\UserController@user_assign_submodule')->middleware('can:Dashboard.User.Assign_submodule')->name('Dashboard.User.Assign_submodule');
    Route::get('/Dashboard/User/Hide/SubModule/{id}', 'App\Http\Controllers\UserController@hide_submodule')->middleware('can:Dashboard.User.Hide.SubModule')->name('Dashboard.User.Hide.SubModule');
    Route::post('/Dashboard/User/Hide/SubModule/allsubmodule', 'App\Http\Controllers\UserController@hide_allsubmodule')->middleware('can:Dashboard.User.Hide.SubModule.allsubmodule')->name('Dashboard.User.Hide.SubModule.allsubmodule');
    Route::post('Dashboard/User/Unssign_submodule/{id}', 'App\Http\Controllers\UserController@user_unssign_submodule')->middleware('can:Dashboard.User.Unssign_submodule')->name('Dashboard.User.Unssign_submodule');
    Route::post('/Dashboard/User/Destroy/{id}', 'App\Http\Controllers\UserController@destroy')->middleware('can:Dashboard.User.Destroy')->name('Dashboard.User.Destroy');
    Route::post('/Dashboard/User/Restore/{id}', 'App\Http\Controllers\UserController@restore')->middleware('can:Dashboard.User.Restore')->name('Dashboard.User.Restore');
    Route::get('/Dashboard/User/Index/Inactivos', 'App\Http\Controllers\UserController@archive')->middleware('can:Dashboard.User.Inactivos')->name('Dashboard.User.Inactivos');
    /* end */

    /* ROL */
    Route::get('/Dashboard/Rol/Index', 'App\Http\Controllers\RolController@index')->middleware('can:Dashboard.Rol.Index')->name('Dashboard.Rol.Index');
    Route::post('/Dashboard/Rol/Store', 'App\Http\Controllers\RolController@store')->middleware('can:Dashboard.Rol.Store')->name('Dashboard.Rol.Store');
    Route::get('/Dashboard/Rol/Show/{id}', 'App\Http\Controllers\RolController@show')->middleware('can:Dashboard.Rol.Show')->name('Dashboard.Rol.Show');
    Route::post('Dashboard/Rol/Assign_permission/{id}', 'App\Http\Controllers\RolController@rol_assign_permission')->middleware('can:Dashboard.Rol.Assign_permission')->name('Dashboard.Rol.Assign_permission');
    Route::get('/Dashboard/Rol/Hide/{id}', 'App\Http\Controllers\RolController@hide')->middleware('can:Dashboard.Rol.Hide')->name('Dashboard.Rol.Hide');
    Route::post('Dashboard/Rol/Unssign_permission/{id}', 'App\Http\Controllers\RolController@rol_unssign_permission')->middleware('can:Dashboard.Rol.Unssign_permission')->name('Dashboard.Rol.Unssign_permission');
    Route::get('/Dashboard/Rol/Edit/{id}', 'App\Http\Controllers\RolController@edit')->middleware('can:Dashboard.Rol.Edit')->name('Dashboard.Rol.Edit');
    Route::post('/Dashboard/Rol/Update/{id}', 'App\Http\Controllers\RolController@update')->middleware('can:Dashboard.Rol.Update')->name('Dashboard.Rol.Update');
    Route::post('/Dashboard/Rol/Destroy/{id}', 'App\Http\Controllers\RolController@destroy')->middleware('can:Dashboard.Rol.Destroy')->name('Dashboard.Rol.Destroy');
    /* end */

    /* PERMISSION */
    Route::get('/Dashboard/Permission/Index', 'App\Http\Controllers\PermissionController@index')->middleware('can:Dashboard.Permission.Index')->name('Dashboard.Permission.Index');
    Route::post('/Dashboard/Permission/Store', 'App\Http\Controllers\PermissionController@store')->middleware('can:Dashboard.Permission.Store')->name('Dashboard.Permission.Store');
    Route::post('/Dashboard/Permission/Update/{id}', 'App\Http\Controllers\PermissionController@update')->middleware('can:Dashboard.Permission.Update')->name('Dashboard.Permission.Update');
    Route::post('/Dashboard/Permission/Destroy/{id}', 'App\Http\Controllers\PermissionController@destroy')->middleware('can:Dashboard.Permission.Destroy')->name('Dashboard.Permission.Destroy');
    /* end */

    Route::get('/Dashboard/Module/Index', 'App\Http\Controllers\ModuleController@index')->middleware('can:Dashboard.Module.Index')->name('Dashboard.Module.Index');
    Route::post('/Dashboard/Module/Store', 'App\Http\Controllers\ModuleController@store')->middleware('can:Dashboard.Module.Store')->name('Dashboard.Module.Store');
    Route::post('/Dashboard/Module/Update/{id}', 'App\Http\Controllers\ModuleController@update')->middleware('can:Dashboard.Module.Update')->name('Dashboard.Module.Update');
    Route::post('/Dashboard/Module/Destroy/{id}', 'App\Http\Controllers\ModuleController@destroy')->middleware('can:Dashboard.Module.Destroy')->name('Dashboard.Module.Destroy');
    Route::get('/Dashboard/Module/Show/{id}', 'App\Http\Controllers\ModuleController@show')->middleware('can:Dashboard.Module.Show')->name('Dashboard.Module.Show');
    Route::post('/Dashboard/Module/Assign_rol/{id}', 'App\Http\Controllers\ModuleController@module_assign_rol')->middleware('can:Dashboard.Module.Assign_rol')->name('Dashboard.Module.Assign_rol');
    Route::get('/Dashboard/Module/Hide/{id}', 'App\Http\Controllers\ModuleController@hide')->middleware('can:Dashboard.Module.Hide')->name('Dashboard.Module.Hide');
    Route::post('/Dashboard/Module/Unsign_rol/{id}', 'App\Http\Controllers\ModuleController@module_unssign_rol')->middleware('can:Dashboard.Module.Unsign_rol')->name('Dashboard.Module.Unssign_rol');

    Route::get('/Dashboard/SubModule/Index', 'App\Http\Controllers\SubModulesController@index')->middleware('can:Dashboard.SubModule.Index')->name('Dashboard.SubModule.Index');
    Route::post('/Dashboard/SubModule/Store', 'App\Http\Controllers\SubModulesController@store')->middleware('can:Dashboard.SubModule.Store')->name('Dashboard.SubModule.Store');
    Route::post('/Dashboard/SubModule/Update/{id}', 'App\Http\Controllers\SubModulesController@update')->middleware('can:Dashboard.SubModule.Update')->name('Dashboard.SubModule.Update');
    Route::post('/Dashboard/SubModule/Destroy/{id}', 'App\Http\Controllers\SubModulesController@destroy')->middleware('can:Dashboard.SubModule.Destroy')->name('Dashboard.SubModule.Destroy');
    Route::get('/Dashboard/SubModule/Show/{id}', 'App\Http\Controllers\SubModulesController@show')->middleware('can:Dashboard.SubModule.Show')->name('Dashboard.SubModule.Show');
    Route::post('/Dashboard/SubModule/Assign_rol/{id}', 'App\Http\Controllers\SubModulesController@module_assign_rol')->middleware('can:Dashboard.SubModule.Assign_rol')->name('Dashboard.SubModule.Assign_rol');
    Route::get('/Dashboard/SubModule/Hide/{id}', 'App\Http\Controllers\SubModulesController@hide')->middleware('can:Dashboard.SubModule.Hide')->name('Dashboard.SubModule.Hide');
    Route::post('/Dashboard/SubModule/Unsign_rol/{id}', 'App\Http\Controllers\SubModulesController@module_unssign_rol')->middleware('can:Dashboard.SubModule.Unsign_rol')->name('Dashboard.SubModule.Unssign_rol');

    Route::get('/Dashboard/Enterprises/Index', 'App\Http\Controllers\EnterprisesController@index')->middleware('can:Dashboard.Enterprises.Index')->name('Dashboard.Enterprises.Index');
    Route::post('/Dashboard/Enterprises/Store', 'App\Http\Controllers\EnterprisesController@store')->middleware('can:Dashboard.Enterprises.Store')->name('Dashboard.Enterprises.Store');
    Route::post('/Dashboard/Enterprises/Update/{id}', 'App\Http\Controllers\EnterprisesController@update')->middleware('can:Dashboard.Enterprises.Update')->name('Dashboard.Enterprises.Update');
    Route::post('/Dashboard/Enterprises/Destroy/{id}', 'App\Http\Controllers\EnterprisesController@destroy')->middleware('can:Dashboard.Enterprises.Destroy')->name('Dashboard.Enterprises.Destroy');
    Route::get('/Dashboard/Enterprises/Show/Users/{id}', 'App\Http\Controllers\EnterprisesController@show_users')->middleware('can:Dashboard.Enterprises.Show.Users')->name('Dashboard.Enterprises.Show.Users');
    Route::post('/Dashboard/Enterprises/Assign_users/{id}', 'App\Http\Controllers\EnterprisesController@enterprise_assign_user')->middleware('can:Dashboard.Enterprises.Assign_users')->name('Dashboard.Enterprises.Assign_users');
    Route::get('/Dashboard/Enterprises/Hide/Users/{id}', 'App\Http\Controllers\EnterprisesController@hide_users')->middleware('can:Dashboard.Enterprises.Hide.Users')->name('Dashboard.Enterprises.Hide.Users');
    Route::post('/Dashboard/Enterprises/Unssign_users/{id}', 'App\Http\Controllers\EnterprisesController@enterprise_unssign_user')->middleware('can:Dashboard.Enterprises.Unssign_users')->name('Dashboard.Enterprises.Unssign_users');
    Route::get('/Dashboard/Enterprises/Show/Modules/{id}', 'App\Http\Controllers\EnterprisesController@show_modules')->middleware('can:Dashboard.Enterprises.Show.Modules')->name('Dashboard.Enterprises.Show.Modules');
    Route::post('/Dashboard/Enterprises/Assign_modules/{id}', 'App\Http\Controllers\EnterprisesController@enterprise_assign_modules')->middleware('can:Dashboard.Enterprises.Assign_modules')->name('Dashboard.Enterprises.Assign_modules');
    Route::get('/Dashboard/Enterprises/Hide/Modules/{id}', 'App\Http\Controllers\EnterprisesController@hide_modules')->middleware('can:Dashboard.Enterprises.Hide.Modules')->name('Dashboard.Enterprises.Hide.Modules');
    Route::post('/Dashboard/Enterprises/Unssign_modules/{id}', 'App\Http\Controllers\EnterprisesController@enterprise_unssign_modules')->middleware('can:Dashboard.Enterprises.Unssign_modules')->name('Dashboard.Enterprises.Unssign_modules');
    Route::get('/Dashboard/Enterprises/Show/SubModules/{id}', 'App\Http\Controllers\EnterprisesController@show_submodules')->middleware('can:Dashboard.Enterprises.Show.SubModules')->name('Dashboard.Enterprises.Show.SubModules');
    Route::post('/Dashboard/Enterprises/Show/SubModule/allsubmodule', 'App\Http\Controllers\EnterprisesController@show_allsubmodules')->middleware('can:Dashboard.Enterprises.Show.SubModule.allsubmodules')->name('Dashboard.Enterprises.Show.SubModule.allsubmodules');
    Route::post('/Dashboard/Enterprises/Assign_submodules/{id}', 'App\Http\Controllers\EnterprisesController@enterprise_assign_submodules')->middleware('can:Dashboard.Enterprises.Assign_submodules')->name('Dashboard.Enterprises.Assign_submodules');
    Route::get('/Dashboard/Enterprises/Hide/SubModules/{id}', 'App\Http\Controllers\EnterprisesController@hide_submodules')->middleware('can:Dashboard.Enterprises.Hide.SubModules')->name('Dashboard.Enterprises.Hide.SubModules');
    Route::post('/Dashboard/Enterprises/Hide/SubModule/allsubmodule', 'App\Http\Controllers\EnterprisesController@hide_allsubmodules')->middleware('can:Dashboard.Enterprises.Hide.SubModule.allsubmodules')->name('Dashboard.Enterprises.Hide.SubModule.allsubmodules');
    Route::post('/Dashboard/Enterprises/Unssign_submodules/{id}', 'App\Http\Controllers\EnterprisesController@enterprise_unssign_submodules')->middleware('can:Dashboard.Enterprises.Unssign_submodules')->name('Dashboard.Enterprises.Unssign_submodules');

    Route::get('/404', function () {
        return view('Dashboard/Exceptions/404');
    });
    Route::get('/403', function () {
        return view('Dashboard/Exceptions/403');
    });
    Route::get('/500', function () {
        return view('Dashboard/Exceptions/500');
    });
});
