<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoriesAndSubcategoriesController;
use App\Http\Controllers\ClothingLineController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ModulesAndSubmodulesController;
use App\Http\Controllers\PackageTypeController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RolesAndPermissionsController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\TrademarkController;
use App\Http\Controllers\TransporterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
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

    Route::prefix('/Dashboard')->group(function () {

        Route::get('/',  [HomeController::class, 'index'])
        ->middleware('can:Dashboard')->name('Dashboard');

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

        Route::prefix('/DocumentTypes')->group(function () {

            Route::get('/Index', [DocumentTypeController::class, 'index'])
            ->middleware('can:Dashboard.DocumentTypes.Index')->name('Dashboard.DocumentTypes.Index');

            Route::post('/Index/Query', [DocumentTypeController::class, 'indexQuery'])
            ->middleware('can:Dashboard.DocumentTypes.Index.Query')->name('Dashboard.DocumentTypes.Index.Query');

            Route::post('/Create', [DocumentTypeController::class, 'create'])
            ->middleware('can:Dashboard.DocumentTypes.Create')->name('Dashboard.DocumentTypes.Create');

            Route::post('/Store', [DocumentTypeController::class, 'store'])
            ->middleware('can:Dashboard.DocumentTypes.Store')->name('Dashboard.DocumentTypes.Store');

            Route::post('/Edit/{id}', [DocumentTypeController::class, 'edit'])
            ->middleware('can:Dashboard.DocumentTypes.Edit')->name('Dashboard.DocumentTypes.Edit');

            Route::put('/Update/{id}', [DocumentTypeController::class, 'update'])
            ->middleware('can:Dashboard.DocumentTypes.Update')->name('Dashboard.DocumentTypes.Update');

            Route::delete('/Delete', [DocumentTypeController::class, 'delete'])
            ->middleware('can:Dashboard.DocumentTypes.Delete')->name('Dashboard.DocumentTypes.Delete');

            Route::put('/Restore', [DocumentTypeController::class, 'restore'])
            ->middleware('can:Dashboard.DocumentTypes.Restore')->name('Dashboard.DocumentTypes.Restore');
        });

        Route::prefix('/Transporters')->group(function () {

            Route::get('/Index', [TransporterController::class, 'index'])
            ->middleware('can:Dashboard.Transporters.Index')->name('Dashboard.Transporters.Index');

            Route::post('/Index/Query', [TransporterController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Transporters.Index.Query')->name('Dashboard.Transporters.Index.Query');

            Route::post('/Create', [TransporterController::class, 'create'])
            ->middleware('can:Dashboard.Transporters.Create')->name('Dashboard.Transporters.Create');

            Route::post('/Store', [TransporterController::class, 'store'])
            ->middleware('can:Dashboard.Transporters.Store')->name('Dashboard.Transporters.Store');

            Route::post('/Edit/{id}', [TransporterController::class, 'edit'])
            ->middleware('can:Dashboard.Transporters.Edit')->name('Dashboard.Transporters.Edit');

            Route::put('/Update/{id}', [TransporterController::class, 'update'])
            ->middleware('can:Dashboard.Transporters.Update')->name('Dashboard.Transporters.Update');

            Route::delete('/Delete', [TransporterController::class, 'delete'])
            ->middleware('can:Dashboard.Transporters.Delete')->name('Dashboard.Transporters.Delete');

            Route::put('/Restore', [TransporterController::class, 'restore'])
            ->middleware('can:Dashboard.Transporters.Restore')->name('Dashboard.Transporters.Restore');
        });

        Route::prefix('/PaymentMethods')->group(function () {

            Route::get('/Index', [PaymentMethodController::class, 'index'])
            ->middleware('can:Dashboard.PaymentMethods.Index')->name('Dashboard.PaymentMethods.Index');

            Route::post('/Index/Query', [PaymentMethodController::class, 'indexQuery'])
            ->middleware('can:Dashboard.PaymentMethods.Index.Query')->name('Dashboard.PaymentMethods.Index.Query');

            Route::post('/Create', [PaymentMethodController::class, 'create'])
            ->middleware('can:Dashboard.PaymentMethods.Create')->name('Dashboard.PaymentMethods.Create');

            Route::post('/Store', [PaymentMethodController::class, 'store'])
            ->middleware('can:Dashboard.PaymentMethods.Store')->name('Dashboard.PaymentMethods.Store');

            Route::post('/Edit/{id}', [PaymentMethodController::class, 'edit'])
            ->middleware('can:Dashboard.PaymentMethods.Edit')->name('Dashboard.PaymentMethods.Edit');

            Route::put('/Update/{id}', [PaymentMethodController::class, 'update'])
            ->middleware('can:Dashboard.PaymentMethods.Update')->name('Dashboard.PaymentMethods.Update');

            Route::delete('/Delete', [PaymentMethodController::class, 'delete'])
            ->middleware('can:Dashboard.PaymentMethods.Delete')->name('Dashboard.PaymentMethods.Delete');

            Route::put('/Restore', [PaymentMethodController::class, 'restore'])
            ->middleware('can:Dashboard.PaymentMethods.Restore')->name('Dashboard.PaymentMethods.Restore');
        });

        Route::prefix('/PackageTypes')->group(function () {

            Route::get('/Index', [PackageTypeController::class, 'index'])
            ->middleware('can:Dashboard.PackageTypes.Index')->name('Dashboard.PackageTypes.Index');

            Route::post('/Index/Query', [PackageTypeController::class, 'indexQuery'])
            ->middleware('can:Dashboard.PackageTypes.Index.Query')->name('Dashboard.PackageTypes.Index.Query');

            Route::post('/Create', [PackageTypeController::class, 'create'])
            ->middleware('can:Dashboard.PackageTypes.Create')->name('Dashboard.PackageTypes.Create');

            Route::post('/Store', [PackageTypeController::class, 'store'])
            ->middleware('can:Dashboard.PackageTypes.Store')->name('Dashboard.PackageTypes.Store');

            Route::post('/Edit/{id}', [PackageTypeController::class, 'edit'])
            ->middleware('can:Dashboard.PackageTypes.Edit')->name('Dashboard.PackageTypes.Edit');

            Route::put('/Update/{id}', [PackageTypeController::class, 'update'])
            ->middleware('can:Dashboard.PackageTypes.Update')->name('Dashboard.PackageTypes.Update');

            Route::delete('/Delete', [PackageTypeController::class, 'delete'])
            ->middleware('can:Dashboard.PackageTypes.Delete')->name('Dashboard.PackageTypes.Delete');

            Route::put('/Restore', [PackageTypeController::class, 'restore'])
            ->middleware('can:Dashboard.PackageTypes.Restore')->name('Dashboard.PackageTypes.Restore');
        });

        Route::prefix('/Businesses')->group(function () {

            Route::get('/Index', [BusinessController::class, 'index'])
            ->middleware('can:Dashboard.Businesses.Index')->name('Dashboard.Businesses.Index');

            Route::post('/Index/Query', [BusinessController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Businesses.Index.Query')->name('Dashboard.Businesses.Index.Query');

            Route::post('/Create', [BusinessController::class, 'create'])
            ->middleware('can:Dashboard.Businesses.Create')->name('Dashboard.Businesses.Create');

            Route::post('/Store', [BusinessController::class, 'store'])
            ->middleware('can:Dashboard.Businesses.Store')->name('Dashboard.Businesses.Store');

            Route::post('/Edit/{id}', [BusinessController::class, 'edit'])
            ->middleware('can:Dashboard.Businesses.Edit')->name('Dashboard.Businesses.Edit');

            Route::put('/Update/{id}', [BusinessController::class, 'update'])
            ->middleware('can:Dashboard.Businesses.Update')->name('Dashboard.Businesses.Update');

            Route::delete('/Delete', [BusinessController::class, 'delete'])
            ->middleware('can:Dashboard.Businesses.Delete')->name('Dashboard.Businesses.Delete');

            Route::put('/Restore', [BusinessController::class, 'restore'])
            ->middleware('can:Dashboard.Businesses.Restore')->name('Dashboard.Businesses.Restore');
        });

        Route::prefix('/Warehouses')->group(function () {

            Route::get('/Index', [WarehouseController::class, 'index'])
            ->middleware('can:Dashboard.Warehouses.Index')->name('Dashboard.Warehouses.Index');

            Route::post('/Index/Query', [WarehouseController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Warehouses.Index.Query')->name('Dashboard.Warehouses.Index.Query');

            Route::post('/Create', [WarehouseController::class, 'create'])
            ->middleware('can:Dashboard.Warehouses.Create')->name('Dashboard.Warehouses.Create');

            Route::post('/Store', [WarehouseController::class, 'store'])
            ->middleware('can:Dashboard.Warehouses.Store')->name('Dashboard.Warehouses.Store');

            Route::post('/Edit/{id}', [WarehouseController::class, 'edit'])
            ->middleware('can:Dashboard.Warehouses.Edit')->name('Dashboard.Warehouses.Edit');

            Route::put('/Update/{id}', [WarehouseController::class, 'update'])
            ->middleware('can:Dashboard.Warehouses.Update')->name('Dashboard.Warehouses.Update');

            Route::post('/Show/{id}', [WarehouseController::class, 'show'])
            ->middleware('can:Dashboard.Warehouses.Show')->name('Dashboard.Warehouses.Show');

            Route::post('/AssignGestor', [WarehouseController::class, 'assignGestor'])
            ->middleware('can:Dashboard.Warehouses.AssignGestor')->name('Dashboard.Warehouses.AssignGestor');

            Route::post('/RemoveGestor', [WarehouseController::class, 'removeGestor'])
            ->middleware('can:Dashboard.Warehouses.RemoveGestor')->name('Dashboard.Warehouses.RemoveGestor');

            Route::delete('/Delete', [WarehouseController::class, 'delete'])
            ->middleware('can:Dashboard.Warehouses.Delete')->name('Dashboard.Warehouses.Delete');

            Route::put('/Restore', [WarehouseController::class, 'restore'])
            ->middleware('can:Dashboard.Warehouses.Restore')->name('Dashboard.Warehouses.Restore');
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

        Route::prefix('/Sizes')->group(function () {

            Route::get('/Index', [SizeController::class, 'index'])
            ->middleware('can:Dashboard.Sizes.Index')->name('Dashboard.Sizes.Index');

            Route::post('/Index/Query', [SizeController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Sizes.Index.Query')->name('Dashboard.Sizes.Index.Query');

            Route::post('/Create', [SizeController::class, 'create'])
            ->middleware('can:Dashboard.Sizes.Create')->name('Dashboard.Sizes.Create');

            Route::post('/Store', [SizeController::class, 'store'])
            ->middleware('can:Dashboard.Sizes.Store')->name('Dashboard.Sizes.Store');

            Route::post('/Edit/{id}', [SizeController::class, 'edit'])
            ->middleware('can:Dashboard.Sizes.Edit')->name('Dashboard.Sizes.Edit');

            Route::put('/Update/{id}', [SizeController::class, 'update'])
            ->middleware('can:Dashboard.Sizes.Update')->name('Dashboard.Sizes.Update');

            Route::delete('/Delete', [SizeController::class, 'delete'])
            ->middleware('can:Dashboard.Sizes.Delete')->name('Dashboard.Sizes.Delete');

            Route::put('/Restore', [SizeController::class, 'restore'])
            ->middleware('can:Dashboard.Sizes.Restore')->name('Dashboard.Sizes.Restore');
        });

        Route::prefix('/Trademarks')->group(function () {

            Route::get('/Index', [TrademarkController::class, 'index'])
            ->middleware('can:Dashboard.Trademarks.Index')->name('Dashboard.Trademarks.Index');

            Route::post('/Index/Query', [TrademarkController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Trademarks.Index.Query')->name('Dashboard.Trademarks.Index.Query');

            Route::post('/Create', [TrademarkController::class, 'create'])
            ->middleware('can:Dashboard.Trademarks.Create')->name('Dashboard.Trademarks.Create');

            Route::post('/Store', [TrademarkController::class, 'store'])
            ->middleware('can:Dashboard.Trademarks.Store')->name('Dashboard.Trademarks.Store');

            Route::post('/Edit/{id}', [TrademarkController::class, 'edit'])
            ->middleware('can:Dashboard.Trademarks.Edit')->name('Dashboard.Trademarks.Edit');

            Route::post('/Update/{id}', [TrademarkController::class, 'update'])
            ->middleware('can:Dashboard.Trademarks.Update')->name('Dashboard.Trademarks.Update');

            Route::delete('/Delete', [TrademarkController::class, 'delete'])
            ->middleware('can:Dashboard.Trademarks.Delete')->name('Dashboard.Trademarks.Delete');

            Route::put('/Restore', [TrademarkController::class, 'restore'])
            ->middleware('can:Dashboard.Trademarks.Restore')->name('Dashboard.Trademarks.Restore');
        });

        Route::prefix('/Models')->group(function () {

            Route::get('/Index', [ModelController::class, 'index'])
            ->middleware('can:Dashboard.Models.Index')->name('Dashboard.Models.Index');

            Route::post('/Index/Query', [ModelController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Models.Index.Query')->name('Dashboard.Models.Index.Query');

            Route::post('/Create', [ModelController::class, 'create'])
            ->middleware('can:Dashboard.Models.Create')->name('Dashboard.Models.Create');

            Route::post('/Store', [ModelController::class, 'store'])
            ->middleware('can:Dashboard.Models.Store')->name('Dashboard.Models.Store');

            Route::post('/Edit/{id}', [ModelController::class, 'edit'])
            ->middleware('can:Dashboard.Models.Edit')->name('Dashboard.Models.Edit');

            Route::put('/Update/{id}', [ModelController::class, 'update'])
            ->middleware('can:Dashboard.Models.Update')->name('Dashboard.Models.Update');

            Route::delete('/Delete', [ModelController::class, 'delete'])
            ->middleware('can:Dashboard.Models.Delete')->name('Dashboard.Models.Delete');

            Route::put('/Restore', [ModelController::class, 'restore'])
            ->middleware('can:Dashboard.Models.Restore')->name('Dashboard.Models.Restore');
        });

        Route::prefix('/ClothingLines')->group(function () {

            Route::get('/Index', [ClothingLineController::class, 'index'])
            ->middleware('can:Dashboard.ClothingLines.Index')->name('Dashboard.ClothingLines.Index');

            Route::post('/Index/Query', [ClothingLineController::class, 'indexQuery'])
            ->middleware('can:Dashboard.ClothingLines.Index.Query')->name('Dashboard.ClothingLines.Index.Query');

            Route::post('/Create', [ClothingLineController::class, 'create'])
            ->middleware('can:Dashboard.ClothingLines.Create')->name('Dashboard.ClothingLines.Create');

            Route::post('/Store', [ClothingLineController::class, 'store'])
            ->middleware('can:Dashboard.ClothingLines.Store')->name('Dashboard.ClothingLines.Store');

            Route::post('/Edit/{id}', [ClothingLineController::class, 'edit'])
            ->middleware('can:Dashboard.ClothingLines.Edit')->name('Dashboard.ClothingLines.Edit');

            Route::put('/Update/{id}', [ClothingLineController::class, 'update'])
            ->middleware('can:Dashboard.ClothingLines.Update')->name('Dashboard.ClothingLines.Update');

            Route::delete('/Delete', [ClothingLineController::class, 'delete'])
            ->middleware('can:Dashboard.ClothingLines.Delete')->name('Dashboard.ClothingLines.Delete');

            Route::put('/Restore', [ClothingLineController::class, 'restore'])
            ->middleware('can:Dashboard.ClothingLines.Restore')->name('Dashboard.ClothingLines.Restore');
        });

        Route::prefix('/CategoriesAndSubcategories')->group(function () {

            Route::get('/Index', [CategoriesAndSubcategoriesController::class, 'index'])
            ->middleware('can:Dashboard.CategoriesAndSubcategories.Index')->name('Dashboard.CategoriesAndSubcategories.Index');

            Route::post('/Index/Query', [CategoriesAndSubcategoriesController::class, 'indexQuery'])
            ->middleware('can:Dashboard.CategoriesAndSubcategories.Index.Query')->name('Dashboard.CategoriesAndSubcategories.Index.Query');

            Route::post('/Create', [CategoriesAndSubcategoriesController::class, 'create'])
            ->middleware('can:Dashboard.CategoriesAndSubcategories.Create')->name('Dashboard.CategoriesAndSubcategories.Create');

            Route::post('/Store', [CategoriesAndSubcategoriesController::class, 'store'])
            ->middleware('can:Dashboard.CategoriesAndSubcategories.Store')->name('Dashboard.CategoriesAndSubcategories.Store');

            Route::post('/Edit/{id}', [CategoriesAndSubcategoriesController::class, 'edit'])
            ->middleware('can:Dashboard.CategoriesAndSubcategories.Edit')->name('Dashboard.CategoriesAndSubcategories.Edit');

            Route::put('/Update/{id}', [CategoriesAndSubcategoriesController::class, 'update'])
            ->middleware('can:Dashboard.CategoriesAndSubcategories.Update')->name('Dashboard.CategoriesAndSubcategories.Update');

            Route::delete('/Delete', [CategoriesAndSubcategoriesController::class, 'delete'])
            ->middleware('can:Dashboard.CategoriesAndSubcategories.Delete')->name('Dashboard.CategoriesAndSubcategories.Delete');

            Route::put('/Restore', [CategoriesAndSubcategoriesController::class, 'restore'])
            ->middleware('can:Dashboard.CategoriesAndSubcategories.Restore')->name('Dashboard.CategoriesAndSubcategories.Restore');
        });

        Route::prefix('/Colors')->group(function () {

            Route::get('/Index', [ColorController::class, 'index'])
            ->middleware('can:Dashboard.Colors.Index')->name('Dashboard.Colors.Index');

            Route::post('/Index/Query', [ColorController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Colors.Index.Query')->name('Dashboard.Colors.Index.Query');

            Route::post('/Create', [ColorController::class, 'create'])
            ->middleware('can:Dashboard.Colors.Create')->name('Dashboard.Colors.Create');

            Route::post('/Store', [ColorController::class, 'store'])
            ->middleware('can:Dashboard.Colors.Store')->name('Dashboard.Colors.Store');

            Route::post('/Edit/{id}', [ColorController::class, 'edit'])
            ->middleware('can:Dashboard.Colors.Edit')->name('Dashboard.Colors.Edit');

            Route::put('/Update/{id}', [ColorController::class, 'update'])
            ->middleware('can:Dashboard.Colors.Update')->name('Dashboard.Colors.Update');

            Route::delete('/Delete', [ColorController::class, 'delete'])
            ->middleware('can:Dashboard.Colors.Delete')->name('Dashboard.Colors.Delete');

            Route::put('/Restore', [ColorController::class, 'restore'])
            ->middleware('can:Dashboard.Colors.Restore')->name('Dashboard.Colors.Restore');
        });

        Route::prefix('/Products')->group(function () {

            Route::get('/Index', [ProductController::class, 'index'])
            ->middleware('can:Dashboard.Products.Index')->name('Dashboard.Products.Index');

            Route::post('/Index/Query', [ProductController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Products.Index.Query')->name('Dashboard.Products.Index.Query');

            Route::post('/Create', [ProductController::class, 'create'])
            ->middleware('can:Dashboard.Products.Create')->name('Dashboard.Products.Create');

            Route::post('/Store', [ProductController::class, 'store'])
            ->middleware('can:Dashboard.Products.Store')->name('Dashboard.Products.Store');

            Route::post('/Edit/{id}', [ProductController::class, 'edit'])
            ->middleware('can:Dashboard.Products.Edit')->name('Dashboard.Products.Edit');

            Route::post('/Update/{id}', [ProductController::class, 'update'])
            ->middleware('can:Dashboard.Products.Update')->name('Dashboard.Products.Update');

            Route::post('/Show/{id}', [ProductController::class, 'show'])
            ->middleware('can:Dashboard.Products.Show')->name('Dashboard.Products.Show');

            Route::post('/Destroy', [ProductController::class, 'destroy'])
            ->middleware('can:Dashboard.Products.Destroy')->name('Dashboard.Products.Destroy');

            Route::delete('/Delete', [ProductController::class, 'delete'])
            ->middleware('can:Dashboard.Products.Delete')->name('Dashboard.Products.Delete');

            Route::put('/Restore', [ProductController::class, 'restore'])
            ->middleware('can:Dashboard.Products.Restore')->name('Dashboard.Products.Restore');

            Route::post('/Upload', [ProductController::class, 'upload'])
            ->middleware('can:Dashboard.Products.Upload')->name('Dashboard.Products.Upload');

            Route::post('/Download', [ProductController::class, 'download'])
            ->middleware('can:Dashboard.Products.Download')->name('Dashboard.Products.Download');
        });

        Route::prefix('/Inventories')->group(function () {

            Route::get('/Index', [InventoryController::class, 'index'])
            ->middleware('can:Dashboard.Inventories.Index')->name('Dashboard.Inventories.Index');

            Route::post('/Index/Query', [InventoryController::class, 'indexQuery'])
            ->middleware('can:Dashboard.Inventories.Index.Query')->name('Dashboard.Inventories.Index.Query');

            Route::post('/Upload', [InventoryController::class, 'upload'])
            ->middleware('can:Dashboard.Inventories.Upload')->name('Dashboard.Inventories.Upload');

            Route::post('/Download', [InventoryController::class, 'download'])
            ->middleware('can:Dashboard.Inventories.Download')->name('Dashboard.Inventories.Download');
        });
    });
});
