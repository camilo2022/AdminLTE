<?php

use App\Http\Controllers\AreasAndChargesController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoriesAndSubcategoriesController;
use App\Http\Controllers\ClientBranchController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientTypeController;
use App\Http\Controllers\ClothingLineController;
use App\Http\Controllers\CorreriasAndCollectionsController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ModulesAndSubmodulesController;
use App\Http\Controllers\OrderDispatchController;
use App\Http\Controllers\OrderDispatchDetailController;
use App\Http\Controllers\OrderInvoiceController;
use App\Http\Controllers\OrderPackedController;
use App\Http\Controllers\OrderPackedPackageController;
use App\Http\Controllers\OrderSellerController;
use App\Http\Controllers\OrderSellerDetailController;
use App\Http\Controllers\OrderWalletController;
use App\Http\Controllers\OrderWalletDetailController;
use App\Http\Controllers\PackageTypeController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonReferenceController;
use App\Http\Controllers\PersonTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReturnTypeController;
use App\Http\Controllers\RolesAndPermissionsController;
use App\Http\Controllers\SaleChannelController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ToneController;
use App\Http\Controllers\TrademarkController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TransferDetailController;
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

Route::get('Packing/Package/Details/{id}', [ResetPasswordController::class, 'detailPackage'])->name('Packing.Package.Details');

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {

    Route::prefix('/Dashboard')->group(function () {

        Route::controller(HomeController::class)->group(function () {
            Route::get('/', 'index')->middleware('can:Dashboard')->name('Dashboard');
        });

        Route::prefix('/Users')->group(function () {
            Route::controller(UserController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Users.Index')->name('Dashboard.Users.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Users.Index.Query')->name('Dashboard.Users.Index.Query');
                Route::get('/Inactives', 'inactives')->middleware('can:Dashboard.Users.Inactives')->name('Dashboard.Users.Inactives');
                Route::post('/Inactives/Query', 'inactivesQuery')->middleware('can:Dashboard.Users.Inactives.Query')->name('Dashboard.Users.Inactives.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Users.Create')->name('Dashboard.Users.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Users.Store')->name('Dashboard.Users.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Users.Edit')->name('Dashboard.Users.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Users.Update')->name('Dashboard.Users.Update');
                Route::post('/Show/{id}', 'show')->middleware('can:Dashboard.Users.Show')->name('Dashboard.Users.Show');
                Route::put('/Password/{id}', 'password')->middleware('can:Dashboard.Users.Password')->name('Dashboard.Users.Password');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Users.Delete')->name('Dashboard.Users.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Users.Restore')->name('Dashboard.Users.Restore');
                Route::post('/AssignRoleAndPermissions', 'assignRoleAndPermissions')->middleware('can:Dashboard.Users.AssignRoleAndPermissions')->name('Dashboard.Users.AssignRoleAndPermissions');
                Route::post('/AssignRoleAndPermissions/Query', 'assignRoleAndPermissionsQuery')->middleware('can:Dashboard.Users.AssignRoleAndPermissions.Query')->name('Dashboard.Users.AssignRoleAndPermissions.Query');
                Route::post('/RemoveRoleAndPermissions', 'removeRoleAndPermissions')->middleware('can:Dashboard.Users.RemoveRoleAndPermissions')->name('Dashboard.Users.RemoveRoleAndPermissions');
                Route::post('/RemoveRoleAndPermissions/Query', 'removeRoleAndPermissionsQuery')->middleware('can:Dashboard.Users.RemoveRoleAndPermissions.Query')->name('Dashboard.Users.RemoveRoleAndPermissions.Query');
            });
        });

        Route::prefix('/RolesAndPermissions')->group(function () {
            Route::controller(RolesAndPermissionsController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.RolesAndPermissions.Index')->name('Dashboard.RolesAndPermissions.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.RolesAndPermissions.Index.Query')->name('Dashboard.RolesAndPermissions.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.RolesAndPermissions.Create')->name('Dashboard.RolesAndPermissions.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.RolesAndPermissions.Store')->name('Dashboard.RolesAndPermissions.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.RolesAndPermissions.Edit')->name('Dashboard.RolesAndPermissions.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.RolesAndPermissions.Update')->name('Dashboard.RolesAndPermissions.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.RolesAndPermissions.Delete')->name('Dashboard.RolesAndPermissions.Delete');
            });
        });

        Route::prefix('/ModulesAndSubmodules')->group(function () {
            Route::controller(ModulesAndSubmodulesController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.ModulesAndSubmodules.Index')->name('Dashboard.ModulesAndSubmodules.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.ModulesAndSubmodules.Index.Query')->name('Dashboard.ModulesAndSubmodules.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.ModulesAndSubmodules.Create')->name('Dashboard.ModulesAndSubmodules.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.ModulesAndSubmodules.Store')->name('Dashboard.ModulesAndSubmodules.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.ModulesAndSubmodules.Edit')->name('Dashboard.ModulesAndSubmodules.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.ModulesAndSubmodules.Update')->name('Dashboard.ModulesAndSubmodules.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.ModulesAndSubmodules.Delete')->name('Dashboard.ModulesAndSubmodules.Delete');
            });
        });

        Route::prefix('/AreasAndCharges')->group(function () {
            Route::controller(AreasAndChargesController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.AreasAndCharges.Index')->name('Dashboard.AreasAndCharges.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.AreasAndCharges.Index.Query')->name('Dashboard.AreasAndCharges.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.AreasAndCharges.Create')->name('Dashboard.AreasAndCharges.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.AreasAndCharges.Store')->name('Dashboard.AreasAndCharges.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.AreasAndCharges.Edit')->name('Dashboard.AreasAndCharges.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.AreasAndCharges.Update')->name('Dashboard.AreasAndCharges.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.AreasAndCharges.Delete')->name('Dashboard.AreasAndCharges.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.AreasAndCharges.Restore')->name('Dashboard.AreasAndCharges.Restore');
            });
        });

        Route::prefix('/DocumentTypes')->group(function () {
            Route::controller(DocumentTypeController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.DocumentTypes.Index')->name('Dashboard.DocumentTypes.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.DocumentTypes.Index.Query')->name('Dashboard.DocumentTypes.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.DocumentTypes.Create')->name('Dashboard.DocumentTypes.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.DocumentTypes.Store')->name('Dashboard.DocumentTypes.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.DocumentTypes.Edit')->name('Dashboard.DocumentTypes.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.DocumentTypes.Update')->name('Dashboard.DocumentTypes.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.DocumentTypes.Delete')->name('Dashboard.DocumentTypes.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.DocumentTypes.Restore')->name('Dashboard.DocumentTypes.Restore');
            });
        });

        Route::prefix('/ClientTypes')->group(function () {
            Route::controller(ClientTypeController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.ClientTypes.Index')->name('Dashboard.ClientTypes.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.ClientTypes.Index.Query')->name('Dashboard.ClientTypes.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.ClientTypes.Create')->name('Dashboard.ClientTypes.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.ClientTypes.Store')->name('Dashboard.ClientTypes.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.ClientTypes.Edit')->name('Dashboard.ClientTypes.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.ClientTypes.Update')->name('Dashboard.ClientTypes.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.ClientTypes.Delete')->name('Dashboard.ClientTypes.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.ClientTypes.Restore')->name('Dashboard.ClientTypes.Restore');
            });
        });

        Route::prefix('/PersonTypes')->group(function () {
            Route::controller(PersonTypeController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.PersonTypes.Index')->name('Dashboard.PersonTypes.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.PersonTypes.Index.Query')->name('Dashboard.PersonTypes.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.PersonTypes.Create')->name('Dashboard.PersonTypes.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.PersonTypes.Store')->name('Dashboard.PersonTypes.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.PersonTypes.Edit')->name('Dashboard.PersonTypes.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.PersonTypes.Update')->name('Dashboard.PersonTypes.Update');
                Route::post('/Show/{id}', 'show')->middleware('can:Dashboard.PersonTypes.Show')->name('Dashboard.PersonTypes.Show');
                Route::post('/AssignDocumentType', 'assignDocumentType')->middleware('can:Dashboard.PersonTypes.AssignDocumentType')->name('Dashboard.PersonTypes.AssignDocumentType');
                Route::post('/RemoveDocumentType', 'removeDocumentType')->middleware('can:Dashboard.PersonTypes.RemoveDocumentType')->name('Dashboard.PersonTypes.RemoveDocumentType');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.PersonTypes.Delete')->name('Dashboard.PersonTypes.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.PersonTypes.Restore')->name('Dashboard.PersonTypes.Restore');
            });
        });

        Route::prefix('/PackageTypes')->group(function () {
            Route::controller(PackageTypeController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.PackageTypes.Index')->name('Dashboard.PackageTypes.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.PackageTypes.Index.Query')->name('Dashboard.PackageTypes.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.PackageTypes.Create')->name('Dashboard.PackageTypes.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.PackageTypes.Store')->name('Dashboard.PackageTypes.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.PackageTypes.Edit')->name('Dashboard.PackageTypes.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.PackageTypes.Update')->name('Dashboard.PackageTypes.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.PackageTypes.Delete')->name('Dashboard.PackageTypes.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.PackageTypes.Restore')->name('Dashboard.PackageTypes.Restore');
            });
        });

        Route::prefix('/ReturnTypes')->group(function () {
            Route::controller(ReturnTypeController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.ReturnTypes.Index')->name('Dashboard.ReturnTypes.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.ReturnTypes.Index.Query')->name('Dashboard.ReturnTypes.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.ReturnTypes.Create')->name('Dashboard.ReturnTypes.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.ReturnTypes.Store')->name('Dashboard.ReturnTypes.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.ReturnTypes.Edit')->name('Dashboard.ReturnTypes.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.ReturnTypes.Update')->name('Dashboard.ReturnTypes.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.ReturnTypes.Delete')->name('Dashboard.ReturnTypes.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.ReturnTypes.Restore')->name('Dashboard.ReturnTypes.Restore');
            });
        });

        Route::prefix('/Transporters')->group(function () {
            Route::controller(TransporterController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Transporters.Index')->name('Dashboard.Transporters.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Transporters.Index.Query')->name('Dashboard.Transporters.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Transporters.Create')->name('Dashboard.Transporters.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Transporters.Store')->name('Dashboard.Transporters.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Transporters.Edit')->name('Dashboard.Transporters.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Transporters.Update')->name('Dashboard.Transporters.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Transporters.Delete')->name('Dashboard.Transporters.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Transporters.Restore')->name('Dashboard.Transporters.Restore');
            });
        });

        Route::prefix('/Banks')->group(function () {
            Route::controller(BankController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Banks.Index')->name('Dashboard.Banks.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Banks.Index.Query')->name('Dashboard.Banks.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Banks.Create')->name('Dashboard.Banks.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Banks.Store')->name('Dashboard.Banks.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Banks.Edit')->name('Dashboard.Banks.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Banks.Update')->name('Dashboard.Banks.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Banks.Delete')->name('Dashboard.Banks.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Banks.Restore')->name('Dashboard.Banks.Restore');
            });
        });

        Route::prefix('/PaymentTypes')->group(function () {
            Route::controller(PaymentTypeController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.PaymentTypes.Index')->name('Dashboard.PaymentTypes.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.PaymentTypes.Index.Query')->name('Dashboard.PaymentTypes.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.PaymentTypes.Create')->name('Dashboard.PaymentTypes.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.PaymentTypes.Store')->name('Dashboard.PaymentTypes.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.PaymentTypes.Edit')->name('Dashboard.PaymentTypes.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.PaymentTypes.Update')->name('Dashboard.PaymentTypes.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.PaymentTypes.Delete')->name('Dashboard.PaymentTypes.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.PaymentTypes.Restore')->name('Dashboard.PaymentTypes.Restore');
            });
        });

        Route::prefix('/Businesses')->group(function () {
            Route::controller(BusinessController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Businesses.Index')->name('Dashboard.Businesses.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Businesses.Index.Query')->name('Dashboard.Businesses.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Businesses.Create')->name('Dashboard.Businesses.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Businesses.Store')->name('Dashboard.Businesses.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Businesses.Edit')->name('Dashboard.Businesses.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Businesses.Update')->name('Dashboard.Businesses.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Businesses.Delete')->name('Dashboard.Businesses.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Businesses.Restore')->name('Dashboard.Businesses.Restore');
            });
        });

        Route::prefix('/Warehouses')->group(function () {
            Route::controller(WarehouseController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Warehouses.Index')->name('Dashboard.Warehouses.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Warehouses.Index.Query')->name('Dashboard.Warehouses.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Warehouses.Create')->name('Dashboard.Warehouses.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Warehouses.Store')->name('Dashboard.Warehouses.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Warehouses.Edit')->name('Dashboard.Warehouses.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Warehouses.Update')->name('Dashboard.Warehouses.Update');
                Route::post('/Show/{id}', 'show')->middleware('can:Dashboard.Warehouses.Show')->name('Dashboard.Warehouses.Show');
                Route::post('/AssignGestor', 'assignGestor')->middleware('can:Dashboard.Warehouses.AssignGestor')->name('Dashboard.Warehouses.AssignGestor');
                Route::post('/RemoveGestor', 'removeGestor')->middleware('can:Dashboard.Warehouses.RemoveGestor')->name('Dashboard.Warehouses.RemoveGestor');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Warehouses.Delete')->name('Dashboard.Warehouses.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Warehouses.Restore')->name('Dashboard.Warehouses.Restore');
            });
        });

        Route::prefix('/CorreriasAndCollections')->group(function () {
            Route::controller(CorreriasAndCollectionsController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.CorreriasAndCollections.Index')->name('Dashboard.CorreriasAndCollections.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.CorreriasAndCollections.Index.Query')->name('Dashboard.CorreriasAndCollections.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.CorreriasAndCollections.Create')->name('Dashboard.CorreriasAndCollections.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.CorreriasAndCollections.Store')->name('Dashboard.CorreriasAndCollections.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.CorreriasAndCollections.Edit')->name('Dashboard.CorreriasAndCollections.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.CorreriasAndCollections.Update')->name('Dashboard.CorreriasAndCollections.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.CorreriasAndCollections.Delete')->name('Dashboard.CorreriasAndCollections.Delete');
            });
        });

        Route::prefix('/SaleChannels')->group(function () {
            Route::controller(SaleChannelController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.SaleChannels.Index')->name('Dashboard.SaleChannels.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.SaleChannels.Index.Query')->name('Dashboard.SaleChannels.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.SaleChannels.Create')->name('Dashboard.SaleChannels.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.SaleChannels.Store')->name('Dashboard.SaleChannels.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.SaleChannels.Edit')->name('Dashboard.SaleChannels.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.SaleChannels.Update')->name('Dashboard.SaleChannels.Update');
                Route::post('/Show/{id}', 'show')->middleware('can:Dashboard.SaleChannels.Show')->name('Dashboard.SaleChannels.Show');
                Route::post('/AssignReturnType', 'assignReturnType')->middleware('can:Dashboard.SaleChannels.AssignReturnType')->name('Dashboard.SaleChannels.AssignReturnType');
                Route::post('/RemoveReturnType', 'removeReturnType')->middleware('can:Dashboard.SaleChannels.RemoveReturnType')->name('Dashboard.SaleChannels.RemoveReturnType');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.SaleChannels.Delete')->name('Dashboard.SaleChannels.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.SaleChannels.Restore')->name('Dashboard.SaleChannels.Restore');
            });
        });

        Route::prefix('/Sizes')->group(function () {
            Route::controller(SizeController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Sizes.Index')->name('Dashboard.Sizes.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Sizes.Index.Query')->name('Dashboard.Sizes.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Sizes.Create')->name('Dashboard.Sizes.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Sizes.Store')->name('Dashboard.Sizes.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Sizes.Edit')->name('Dashboard.Sizes.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Sizes.Update')->name('Dashboard.Sizes.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Sizes.Delete')->name('Dashboard.Sizes.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Sizes.Restore')->name('Dashboard.Sizes.Restore');
            });
        });

        Route::prefix('/Trademarks')->group(function () {
            Route::controller(TrademarkController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Trademarks.Index')->name('Dashboard.Trademarks.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Trademarks.Index.Query')->name('Dashboard.Trademarks.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Trademarks.Create')->name('Dashboard.Trademarks.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Trademarks.Store')->name('Dashboard.Trademarks.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Trademarks.Edit')->name('Dashboard.Trademarks.Edit');
                Route::post('/Update/{id}', 'update')->middleware('can:Dashboard.Trademarks.Update')->name('Dashboard.Trademarks.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Trademarks.Delete')->name('Dashboard.Trademarks.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Trademarks.Restore')->name('Dashboard.Trademarks.Restore');
            });
        });

        Route::prefix('/Models')->group(function () {
            Route::controller(ModelController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Models.Index')->name('Dashboard.Models.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Models.Index.Query')->name('Dashboard.Models.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Models.Create')->name('Dashboard.Models.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Models.Store')->name('Dashboard.Models.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Models.Edit')->name('Dashboard.Models.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Models.Update')->name('Dashboard.Models.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Models.Delete')->name('Dashboard.Models.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Models.Restore')->name('Dashboard.Models.Restore');
            });
        });

        Route::prefix('/ClothingLines')->group(function () {
            Route::controller(ClothingLineController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.ClothingLines.Index')->name('Dashboard.ClothingLines.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.ClothingLines.Index.Query')->name('Dashboard.ClothingLines.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.ClothingLines.Create')->name('Dashboard.ClothingLines.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.ClothingLines.Store')->name('Dashboard.ClothingLines.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.ClothingLines.Edit')->name('Dashboard.ClothingLines.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.ClothingLines.Update')->name('Dashboard.ClothingLines.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.ClothingLines.Delete')->name('Dashboard.ClothingLines.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.ClothingLines.Restore')->name('Dashboard.ClothingLines.Restore');
            });
        });

        Route::prefix('/CategoriesAndSubcategories')->group(function () {
            Route::controller(CategoriesAndSubcategoriesController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.CategoriesAndSubcategories.Index')->name('Dashboard.CategoriesAndSubcategories.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.CategoriesAndSubcategories.Index.Query')->name('Dashboard.CategoriesAndSubcategories.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.CategoriesAndSubcategories.Create')->name('Dashboard.CategoriesAndSubcategories.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.CategoriesAndSubcategories.Store')->name('Dashboard.CategoriesAndSubcategories.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.CategoriesAndSubcategories.Edit')->name('Dashboard.CategoriesAndSubcategories.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.CategoriesAndSubcategories.Update')->name('Dashboard.CategoriesAndSubcategories.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.CategoriesAndSubcategories.Delete')->name('Dashboard.CategoriesAndSubcategories.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.CategoriesAndSubcategories.Restore')->name('Dashboard.CategoriesAndSubcategories.Restore');
            });
        });

        Route::prefix('/Colors')->group(function () {
            Route::controller(ColorController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Colors.Index')->name('Dashboard.Colors.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Colors.Index.Query')->name('Dashboard.Colors.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Colors.Create')->name('Dashboard.Colors.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Colors.Store')->name('Dashboard.Colors.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Colors.Edit')->name('Dashboard.Colors.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Colors.Update')->name('Dashboard.Colors.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Colors.Delete')->name('Dashboard.Colors.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Colors.Restore')->name('Dashboard.Colors.Restore');
            });
        });

        Route::prefix('/Tones')->group(function () {
            Route::controller(ToneController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Tones.Index')->name('Dashboard.Tones.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Tones.Index.Query')->name('Dashboard.Tones.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Tones.Create')->name('Dashboard.Tones.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Tones.Store')->name('Dashboard.Tones.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Tones.Edit')->name('Dashboard.Tones.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Tones.Update')->name('Dashboard.Tones.Update');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Tones.Delete')->name('Dashboard.Tones.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Tones.Restore')->name('Dashboard.Tones.Restore');
            });
        });

        Route::prefix('/Products')->group(function () {
            Route::controller(ProductController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Products.Index')->name('Dashboard.Products.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Products.Index.Query')->name('Dashboard.Products.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Products.Create')->name('Dashboard.Products.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Products.Store')->name('Dashboard.Products.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Products.Edit')->name('Dashboard.Products.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Products.Update')->name('Dashboard.Products.Update');
                Route::post('/Show/{id}', 'show')->middleware('can:Dashboard.Products.Show')->name('Dashboard.Products.Show');
                Route::post('/AssignSize', 'assignSize')->middleware('can:Dashboard.Products.AssignSize')->name('Dashboard.Products.AssignSize');
                Route::delete('/RemoveSize', 'removeSize')->middleware('can:Dashboard.Products.RemoveSize')->name('Dashboard.Products.RemoveSize');
                Route::post('/AssignColorTone', 'assignColorTone')->middleware('can:Dashboard.Products.AssignColorTone')->name('Dashboard.Products.AssignColorTone');
                Route::delete('/RemoveColorTone', 'removeColorTone')->middleware('can:Dashboard.Products.RemoveColorTone')->name('Dashboard.Products.RemoveColorTone');
                Route::post('/Charge', 'charge')->middleware('can:Dashboard.Products.Charge')->name('Dashboard.Products.Charge');
                Route::delete('/Destroy', 'destroy')->middleware('can:Dashboard.Products.Destroy')->name('Dashboard.Products.Destroy');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Products.Delete')->name('Dashboard.Products.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Products.Restore')->name('Dashboard.Products.Restore');
                Route::post('/Upload', 'upload')->middleware('can:Dashboard.Products.Upload')->name('Dashboard.Products.Upload');
                Route::post('/Download', 'download')->middleware('can:Dashboard.Products.Download')->name('Dashboard.Products.Download');
            });
        });

        Route::prefix('/Inventories')->group(function () {
            Route::controller(InventoryController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Inventories.Index')->name('Dashboard.Inventories.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Inventories.Index.Query')->name('Dashboard.Inventories.Index.Query');
                Route::post('/Upload', 'upload')->middleware('can:Dashboard.Inventories.Upload')->name('Dashboard.Inventories.Upload');
                Route::post('/Download', 'download')->middleware('can:Dashboard.Inventories.Download')->name('Dashboard.Inventories.Download');
            });
        });

        Route::prefix('/Transfers')->group(function () {
            Route::controller(TransferController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Transfers.Index')->name('Dashboard.Transfers.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Transfers.Index.Query')->name('Dashboard.Transfers.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Transfers.Create')->name('Dashboard.Transfers.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Transfers.Store')->name('Dashboard.Transfers.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Transfers.Edit')->name('Dashboard.Transfers.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Transfers.Update')->name('Dashboard.Transfers.Update');
                Route::post('/Show/{id}', 'show')->middleware('can:Dashboard.Transfers.Show')->name('Dashboard.Transfers.Show');
                Route::post('/Destroy', 'destroy')->middleware('can:Dashboard.Transfers.Destroy')->name('Dashboard.Transfers.Destroy');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Transfers.Delete')->name('Dashboard.Transfers.Delete');
                Route::put('/Approve', 'approve')->middleware('can:Dashboard.Transfers.Approve')->name('Dashboard.Transfers.Approve');
                Route::put('/Cancel', 'cancel')->middleware('can:Dashboard.Transfers.Cancel')->name('Dashboard.Transfers.Cancel');
            });
            Route::prefix('/Details')->group(function () {
                Route::controller(TransferDetailController::class)->group(function () {
                    Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Transfers.Details.Index.Query')->name('Dashboard.Transfers.Details.Index.Query');
                    Route::post('/Create', 'create')->middleware('can:Dashboard.Transfers.Details.Create')->name('Dashboard.Transfers.Details.Create');
                    Route::post('/Store', 'store')->middleware('can:Dashboard.Transfers.Details.Store')->name('Dashboard.Transfers.Details.Store');
                    Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Transfers.Details.Edit')->name('Dashboard.Transfers.Details.Edit');
                    Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Transfers.Details.Update')->name('Dashboard.Transfers.Details.Update');
                    Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Transfers.Details.Delete')->name('Dashboard.Transfers.Details.Delete');
                    Route::put('/Pending', 'pending')->middleware('can:Dashboard.Transfers.Details.Pending')->name('Dashboard.Transfers.Details.Pending');
                    Route::put('/Cancel', 'cancel')->middleware('can:Dashboard.Transfers.Details.Cancel')->name('Dashboard.Transfers.Details.Cancel');
                });
            });
        });

        Route::prefix('/Clients')->group(function () {
            Route::controller(ClientController::class)->group(function () {
                Route::get('/Index', 'index')->middleware('can:Dashboard.Clients.Index')->name('Dashboard.Clients.Index');
                Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Clients.Index.Query')->name('Dashboard.Clients.Index.Query');
                Route::post('/Create', 'create')->middleware('can:Dashboard.Clients.Create')->name('Dashboard.Clients.Create');
                Route::post('/Store', 'store')->middleware('can:Dashboard.Clients.Store')->name('Dashboard.Clients.Store');
                Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Clients.Edit')->name('Dashboard.Clients.Edit');
                Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Clients.Update')->name('Dashboard.Clients.Update');
                Route::post('/Show/{id}', 'show')->middleware('can:Dashboard.Clients.Show')->name('Dashboard.Clients.Show');
                Route::post('/Show/Query/{id}', 'showQuery')->middleware('can:Dashboard.Clients.Show.Query')->name('Dashboard.Clients.Show.Query');
                Route::post('/Quota', 'quota')->middleware('can:Dashboard.Clients.Quota')->name('Dashboard.Clients.Quota');
                Route::put('/Quota/Query', 'quotaQuery')->middleware('can:Dashboard.Clients.Quota.Query')->name('Dashboard.Clients.Quota.Query');
                Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Clients.Delete')->name('Dashboard.Clients.Delete');
                Route::put('/Restore', 'restore')->middleware('can:Dashboard.Clients.Restore')->name('Dashboard.Clients.Restore');
            });
            Route::prefix('/Branches')->group(function () {
                Route::controller(ClientBranchController::class)->group(function () {
                    Route::post('/Index', 'index')->middleware('can:Dashboard.Clients.Branches.Index')->name('Dashboard.Clients.Branches.Index');
                    Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Clients.Branches.Index.Query')->name('Dashboard.Clients.Branches.Index.Query');
                    Route::post('/Create', 'create')->middleware('can:Dashboard.Clients.Branches.Create')->name('Dashboard.Clients.Branches.Create');
                    Route::post('/Store', 'store')->middleware('can:Dashboard.Clients.Branches.Store')->name('Dashboard.Clients.Branches.Store');
                    Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Clients.Branches.Edit')->name('Dashboard.Clients.Branches.Edit');
                    Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Clients.Branches.Update')->name('Dashboard.Clients.Branches.Update');
                    Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Clients.Branches.Delete')->name('Dashboard.Clients.Branches.Delete');
                    Route::put('/Restore', 'restore')->middleware('can:Dashboard.Clients.Branches.Restore')->name('Dashboard.Clients.Branches.Restore');
                });
            });
            Route::prefix('/People')->group(function () {
                Route::controller(PersonController::class)->group(function () {
                    Route::post('/Create', 'create')->middleware('can:Dashboard.Clients.People.Create')->name('Dashboard.Clients.People.Create');
                    Route::post('/Store', 'store')->middleware('can:Dashboard.Clients.People.Store')->name('Dashboard.Clients.People.Store');
                    Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Clients.People.Edit')->name('Dashboard.Clients.People.Edit');
                    Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Clients.People.Update')->name('Dashboard.Clients.People.Update');
                });
                Route::prefix('/References')->group(function () {
                    Route::controller(PersonReferenceController::class)->group(function () {
                        Route::post('/Index', 'index')->middleware('can:Dashboard.Clients.People.References.Index')->name('Dashboard.Clients.People.References.Index');
                        Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Clients.People.References.Index.Query')->name('Dashboard.Clients.People.References.Index.Query');
                        Route::post('/Create', 'create')->middleware('can:Dashboard.Clients.People.References.Create')->name('Dashboard.Clients.People.References.Create');
                        Route::post('/Store', 'store')->middleware('can:Dashboard.Clients.People.References.Store')->name('Dashboard.Clients.People.References.Store');
                        Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Clients.People.References.Edit')->name('Dashboard.Clients.People.References.Edit');
                        Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Clients.People.References.Update')->name('Dashboard.Clients.People.References.Update');
                        Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Clients.People.References.Delete')->name('Dashboard.Clients.People.References.Delete');
                        Route::put('/Restore', 'restore')->middleware('can:Dashboard.Clients.People.References.Restore')->name('Dashboard.Clients.People.References.Restore');
                    });
                });
            });
        });

        Route::prefix('/Orders')->group(function () {
            Route::prefix('/Seller')->group(function () {
                Route::controller(OrderSellerController::class)->group(function () {
                    Route::get('/Index', 'index')->middleware('can:Dashboard.Orders.Seller.Index')->name('Dashboard.Orders.Seller.Index');
                    Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Orders.Seller.Index.Query')->name('Dashboard.Orders.Seller.Index.Query');
                    Route::post('/Create', 'create')->middleware('can:Dashboard.Orders.Seller.Create')->name('Dashboard.Orders.Seller.Create');
                    Route::post('/Store', 'store')->middleware('can:Dashboard.Orders.Seller.Store')->name('Dashboard.Orders.Seller.Store');
                    Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Orders.Seller.Edit')->name('Dashboard.Orders.Seller.Edit');
                    Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Orders.Seller.Update')->name('Dashboard.Orders.Seller.Update');
                    Route::put('/Approve', 'approve')->middleware('can:Dashboard.Orders.Seller.Approve')->name('Dashboard.Orders.Seller.Approve');
                    Route::put('/Pending', 'pending')->middleware('can:Dashboard.Orders.Seller.Pending')->name('Dashboard.Orders.Seller.Pending');
                    Route::put('/Cancel', 'cancel')->middleware('can:Dashboard.Orders.Seller.Cancel')->name('Dashboard.Orders.Seller.Cancel');
                    Route::post('/Payments/Query', 'paymentQuery')->middleware('can:Dashboard.Orders.Seller.Payments.Query')->name('Dashboard.Orders.Seller.Payments.Query');
                    Route::post('/AssignPayment/Query', 'assignPaymentQuery')->middleware('can:Dashboard.Orders.Seller.AssignPayment.Query')->name('Dashboard.Orders.Seller.AssignPayment.Query');
                    Route::post('/AssignPayment', 'assignPayment')->middleware('can:Dashboard.Orders.Seller.AssignPayment')->name('Dashboard.Orders.Seller.AssignPayment');
                    Route::delete('/RemovePayment', 'removePayment')->middleware('can:Dashboard.Orders.Seller.RemovePayment')->name('Dashboard.Orders.Seller.RemovePayment');
                    Route::put('/ApprovePayment', 'approvePayment')->middleware('can:Dashboard.Orders.Seller.ApprovePayment')->name('Dashboard.Orders.Seller.ApprovePayment');
                    Route::put('/CancelPayment', 'cancelPayment')->middleware('can:Dashboard.Orders.Seller.CancelPayment')->name('Dashboard.Orders.Seller.CancelPayment');
                });
                Route::prefix('/Details')->group(function () {
                    Route::controller(OrderSellerDetailController::class)->group(function () {
                        Route::get('/Index/{id}', 'index')->middleware('can:Dashboard.Orders.Seller.Details.Index')->name('Dashboard.Orders.Seller.Details.Index');
                        Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Orders.Seller.Details.Index.Query')->name('Dashboard.Orders.Seller.Details.Index.Query');
                        Route::post('/Create', 'create')->middleware('can:Dashboard.Orders.Seller.Details.Create')->name('Dashboard.Orders.Seller.Details.Create');
                        Route::post('/Store', 'store')->middleware('can:Dashboard.Orders.Seller.Details.Store')->name('Dashboard.Orders.Seller.Details.Store');
                        Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Orders.Seller.Details.Edit')->name('Dashboard.Orders.Seller.Details.Edit');
                        Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Orders.Seller.Details.Update')->name('Dashboard.Orders.Seller.Details.Update');
                        Route::put('/Pending', 'pending')->middleware('can:Dashboard.Orders.Seller.Details.Pending')->name('Dashboard.Orders.Seller.Details.Pending');
                        Route::put('/Cancel', 'cancel')->middleware('can:Dashboard.Orders.Seller.Details.Cancel')->name('Dashboard.Orders.Seller.Details.Cancel');
                    });
                });
            });
            Route::prefix('/Wallet')->group(function () {
                Route::controller(OrderWalletController::class)->group(function () {
                    Route::get('/Index', 'index')->middleware('can:Dashboard.Orders.Wallet.Index')->name('Dashboard.Orders.Wallet.Index');
                    Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Orders.Wallet.Index.Query')->name('Dashboard.Orders.Wallet.Index.Query');
                    Route::put('/Observation', 'observation')->middleware('can:Dashboard.Orders.Wallet.Observation')->name('Dashboard.Orders.Wallet.Observation');
                    Route::put('/Approve', 'approve')->middleware('can:Dashboard.Orders.Wallet.Approve')->name('Dashboard.Orders.Wallet.Approve');
                    Route::put('/PartiallyApprove', 'partiallyApprove')->middleware('can:Dashboard.Orders.Wallet.PartiallyApprove')->name('Dashboard.Orders.Wallet.PartiallyApprove');
                    Route::put('/Pending', 'pending')->middleware('can:Dashboard.Orders.Wallet.Pending')->name('Dashboard.Orders.Wallet.Pending');
                    Route::put('/Cancel', 'cancel')->middleware('can:Dashboard.Orders.Wallet.Cancel')->name('Dashboard.Orders.Wallet.Cancel');
                    Route::post('/Payments/Query', 'paymentQuery')->middleware('can:Dashboard.Orders.Wallet.Payments.Query')->name('Dashboard.Orders.Wallet.Payments.Query');
                });
                Route::prefix('/Details')->group(function () {
                    Route::controller(OrderWalletDetailController::class)->group(function () {
                        Route::get('/Index/{id}', 'index')->middleware('can:Dashboard.Orders.Wallet.Details.Index')->name('Dashboard.Orders.Wallet.Details.Index');
                        Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Orders.Wallet.Details.Index.Query')->name('Dashboard.Orders.Wallet.Details.Index.Query');
                        Route::post('/Create', 'create')->middleware('can:Dashboard.Orders.Wallet.Details.Create')->name('Dashboard.Orders.Wallet.Details.Create');
                        Route::post('/Store', 'store')->middleware('can:Dashboard.Orders.Wallet.Details.Store')->name('Dashboard.Orders.Wallet.Details.Store');
                        Route::post('/Edit/{id}', 'edit')->middleware('can:Dashboard.Orders.Wallet.Details.Edit')->name('Dashboard.Orders.Wallet.Details.Edit');
                        Route::put('/Update/{id}', 'update')->middleware('can:Dashboard.Orders.Wallet.Details.Update')->name('Dashboard.Orders.Wallet.Details.Update');
                        Route::put('/Approve', 'approve')->middleware('can:Dashboard.Orders.Wallet.Details.Approve')->name('Dashboard.Orders.Wallet.Details.Approve');
                        Route::put('/Pending', 'pending')->middleware('can:Dashboard.Orders.Wallet.Details.Pending')->name('Dashboard.Orders.Wallet.Details.Pending');
                        Route::put('/Review', 'review')->middleware('can:Dashboard.Orders.Wallet.Details.Review')->name('Dashboard.Orders.Wallet.Details.Review');
                        Route::put('/Cancel', 'cancel')->middleware('can:Dashboard.Orders.Wallet.Details.Cancel')->name('Dashboard.Orders.Wallet.Details.Cancel');
                        Route::put('/Decline', 'decline')->middleware('can:Dashboard.Orders.Wallet.Details.Decline')->name('Dashboard.Orders.Wallet.Details.Decline');
                    });
                });
            });
            Route::prefix('/Dispatch')->group(function () {
                Route::controller(OrderDispatchController::class)->group(function () {
                    Route::get('/Index', 'index')->middleware('can:Dashboard.Orders.Dispatch.Index')->name('Dashboard.Orders.Dispatch.Index');
                    Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Orders.Dispatch.Index.Query')->name('Dashboard.Orders.Dispatch.Index.Query');
                    Route::get('/Filter/{id}', 'filter')->middleware('can:Dashboard.Orders.Dispatch.Filter')->name('Dashboard.Orders.Dispatch.Filter');
                    Route::post('/Filter/Query/Details', 'filterQueryDetails')->middleware('can:Dashboard.Orders.Dispatch.Filter.Query.Details')->name('Dashboard.Orders.Dispatch.Filter.Query.Details');
                    Route::post('/Filter/Query/Inventories', 'filterQueryInventories')->middleware('can:Dashboard.Orders.Dispatch.Filter.Query.Inventories')->name('Dashboard.Orders.Dispatch.Filter.Query.Inventories');
                    Route::post('/Store', 'store')->middleware('can:Dashboard.Orders.Dispatch.Store')->name('Dashboard.Orders.Dispatch.Store');
                    Route::put('/Pending', 'pending')->middleware('can:Dashboard.Orders.Dispatch.Pending')->name('Dashboard.Orders.Dispatch.Pending');
                    Route::put('/Approve', 'approve')->middleware('can:Dashboard.Orders.Dispatch.Approve')->name('Dashboard.Orders.Dispatch.Approve');
                    Route::put('/Cancel', 'cancel')->middleware('can:Dashboard.Orders.Dispatch.Cancel')->name('Dashboard.Orders.Dispatch.Cancel');
                    Route::put('/Decline', 'decline')->middleware('can:Dashboard.Orders.Dispatch.Decline')->name('Dashboard.Orders.Dispatch.Decline');
                    Route::get('/Download/{id}', 'download')->middleware('can:Dashboard.Orders.Dispatch.Download')->name('Dashboard.Orders.Dispatch.Download');
                });
                Route::prefix('/Details')->group(function () {
                    Route::controller(OrderDispatchDetailController::class)->group(function () {
                        Route::get('/Index/{id}', 'index')->middleware('can:Dashboard.Orders.Dispatch.Details.Index')->name('Dashboard.Orders.Dispatch.Details.Index');
                        Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Orders.Dispatch.Details.Index.Query')->name('Dashboard.Orders.Dispatch.Details.Index.Query');
                        Route::put('/Approve', 'approve')->middleware('can:Dashboard.Orders.Dispatch.Details.Approve')->name('Dashboard.Orders.Dispatch.Details.Approve');
                        Route::put('/Pending', 'pending')->middleware('can:Dashboard.Orders.Dispatch.Details.Pending')->name('Dashboard.Orders.Dispatch.Details.Pending');
                        Route::put('/Cancel', 'cancel')->middleware('can:Dashboard.Orders.Dispatch.Details.Cancel')->name('Dashboard.Orders.Dispatch.Details.Cancel');
                        Route::put('/Decline', 'decline')->middleware('can:Dashboard.Orders.Dispatch.Details.Decline')->name('Dashboard.Orders.Dispatch.Details.Decline');
                    });
                });
            });
            Route::prefix('/Packed')->group(function () {
                Route::controller(OrderPackedController::class)->group(function () {
                    Route::get('/Index', 'index')->middleware('can:Dashboard.Orders.Packed.Index')->name('Dashboard.Orders.Packed.Index');
                    Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Orders.Packed.Index.Query')->name('Dashboard.Orders.Packed.Index.Query');
                    Route::post('/Store', 'store')->middleware('can:Dashboard.Orders.Packed.Store')->name('Dashboard.Orders.Packed.Store');
                    Route::put('/Finish', 'finish')->middleware('can:Dashboard.Orders.Packed.Finish')->name('Dashboard.Orders.Packed.Finish');
                    Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Orders.Packed.Delete')->name('Dashboard.Orders.Packed.Delete');
                });
                Route::prefix('/Packages')->group(function () {
                    Route::controller(OrderPackedPackageController::class)->group(function () {
                        Route::get('/Index/{id}', 'index')->middleware('can:Dashboard.Orders.Packed.Package.Index')->name('Dashboard.Orders.Packed.Package.Index');
                        Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Orders.Packed.Package.Index.Query')->name('Dashboard.Orders.Packed.Package.Index.Query');
                        Route::post('/Store', 'store')->middleware('can:Dashboard.Orders.Packed.Package.Store')->name('Dashboard.Orders.Packed.Package.Store');
                        Route::post('/Detail', 'detail')->middleware('can:Dashboard.Orders.Packed.Package.Detail')->name('Dashboard.Orders.Packed.Package.Detail');
                        Route::get('/Show/{id}', 'show')->middleware('can:Dashboard.Orders.Packed.Package.Show')->name('Dashboard.Orders.Packed.Package.Show');
                        Route::post('/Show/Query', 'showQuery')->middleware('can:Dashboard.Orders.Packed.Package.Show.Query')->name('Dashboard.Orders.Packed.Package.Show.Query');
                        Route::put('/Open', 'open')->middleware('can:Dashboard.Orders.Packed.Package.Open')->name('Dashboard.Orders.Packed.Package.Open');
                        Route::put('/Close', 'close')->middleware('can:Dashboard.Orders.Packed.Package.Close')->name('Dashboard.Orders.Packed.Package.Close');
                        Route::delete('/Delete', 'delete')->middleware('can:Dashboard.Orders.Packed.Package.Delete')->name('Dashboard.Orders.Packed.Package.Delete');
                    });
                    
                });
            });
            Route::prefix('/Invoice')->group(function () {
                Route::controller(OrderInvoiceController::class)->group(function () {
                    Route::get('/Index', 'index')->middleware('can:Dashboard.Orders.Invoice.Index')->name('Dashboard.Orders.Invoice.Index');
                    Route::post('/Index/Query', 'indexQuery')->middleware('can:Dashboard.Orders.Invoice.Index.Query')->name('Dashboard.Orders.Invoice.Index.Query');
                    Route::post('/Create', 'create')->middleware('can:Dashboard.Orders.Invoice.Create')->name('Dashboard.Orders.Invoice.Create');
                    Route::post('/Store', 'store')->middleware('can:Dashboard.Orders.Invoice.Store')->name('Dashboard.Orders.Invoice.Store');
                    Route::get('/Download/{id}', 'download')->middleware('can:Dashboard.Orders.Invoice.Download')->name('Dashboard.Orders.Invoice.Download');
                });
            });

        });

    });

});
