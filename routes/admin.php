<?php

use App\Http\Controllers\backends\AccreController;
use App\Http\Controllers\backends\ActionController;
use App\Http\Controllers\backends\CatesController;
use App\Http\Controllers\backends\ClinicEnvironmentInspectionController;
use App\Http\Controllers\backends\DashboardController;
use App\Http\Controllers\backends\DepartmentController;
use App\Http\Controllers\backends\DeviceController;
use App\Http\Controllers\backends\EqRepairController;
use App\Http\Controllers\backends\ProRepairController;
use App\Http\Controllers\backends\EqsupplieController;
use App\Http\Controllers\backends\EquipmentBallotController;
use App\Http\Controllers\backends\EquipmentController;
use App\Http\Controllers\backends\EqpropertyController;
use App\Http\Controllers\backends\ExternalQualityAssessmentController;
use App\Http\Controllers\backends\GeneralController;
use App\Http\Controllers\backends\GuaranteeController;
use App\Http\Controllers\backends\InventoryController;
use App\Http\Controllers\backends\InventorySupController;
use App\Http\Controllers\backends\LicenseRenewalOfRadiationWorkController;
use App\Http\Controllers\backends\LiquidationController;
use App\Http\Controllers\backends\ProLiquidController;
use App\Http\Controllers\backends\MaintenanceController;
use App\Http\Controllers\backends\ManuallySendNotificationEmails;
use App\Http\Controllers\backends\media\MediaAdminController;
use App\Http\Controllers\backends\media\MediaCatAdminController;
use App\Http\Controllers\backends\NotificationController;
use App\Http\Controllers\backends\OptionController;
use App\Http\Controllers\backends\ProjectController;
use App\Http\Controllers\backends\ProviderController;
use App\Http\Controllers\backends\RadiationInspectionController;
use App\Http\Controllers\backends\RequestController;
use App\Http\Controllers\backends\RoleController;
use App\Http\Controllers\backends\StatisticController;
use App\Http\Controllers\backends\SupplieBallotController;
use App\Http\Controllers\backends\SupplieController;
use App\Http\Controllers\backends\TransferController;
use App\Http\Controllers\backends\UnitController;
use App\Http\Controllers\backends\UserAdminController;
use Illuminate\Support\Facades\Artisan;
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
// Route::get('qr-code', function () {
//     return QrCode::size(500)->generate('Welcome to kerneldev.com!');
// });

//Route::view('/', 'maintenance');

//Route::get('/convert',[EquipmentController::class, 'convert']);
Route::get('/run_migrations', function () {
    return Artisan::call('migrate',
        [
            "--force" => true,
            "--seed" => true
        ]);
});

Route::prefix("/send_emails")->name("send_emails.")->controller(ManuallySendNotificationEmails::class)
    ->group(function () {
        Route::view("/", "backends.manually_send_notification_emails.list")->name("index");
        Route::get('/send_accre_emails', "send_accre_emails")->name("send_accre_emails");
        Route::get('/send_clinic_environment_inspection_emails', "send_clinic_environment_inspection_emails")->name("send_clinic_environment_inspection_emails");
        Route::get('/send_license_renewal_of_radiation_work_emails', "send_license_renewal_of_radiation_work_emails")->name("send_license_renewal_of_radiation_work_emails");
        Route::get('/send_external_quality_assessment_emails', "send_external_quality_assessment_emails")->name("send_external_quality_assessment_emails");
        Route::get('/send_maintenance_emails', "send_maintenance_emails")->name("send_maintenance_emails");
        Route::get('/send_radiation_inspection_emails', "send_radiation_inspection_emails")->name("send_radiation_inspection_emails");
        Route::get('/send_jv_contract_termination_date_emails',  "send_jv_contract_termination_date_emails")->name("send_jv_contract_termination_date_emails");
        Route::get("/send_alls", "send_alls")->name("send_alls");
    });


//update kiểm xạ
//thêm permission, gán permission cho tất cả nvpvt, admin
//Route::get('/update-db-15-9-2022', function () {
//    //creat permission
//    DB::table("permissions")->insert([
//        [
//            'id' => 164,
//            'name' => 'radiation_inspection.read',
//            'guard_name' => "web",
//            'display_name' => "Kiểm xạ thiết bị",
//            "group" => "Kiểm xạ",
//            "sidebar_id" => 125,
//        ],
//    ]);
//    //attach permission
//    DB::table("role_has_permissions")->insert([
//        [
//            "permission_id" => 164,
//            "role_id" => 1, //admin
//        ],
//        [
//            "permission_id" => 164,
//            "role_id" => 9, //nvpvt
//        ],
//        [
//            "permission_id" => 164,
//            "role_id" => 13, //tpvt
//        ],
//        [
//            "permission_id" => 164,
//            "role_id" => 18, //ptpvt
//        ],
//        [
//            "permission_id" => 164,
//            "role_id" => 22, //admin_kienan
//        ],
//        [
//            "permission_id" => 164,
//            "role_id" => 23, //nvpvt-ka
//        ],
//    ]);
//});
//Route::get("/update-db-19-9-2022", function () {
//    DB::table("permissions")->insert([
//        [
//            'id' => 165,
//            'name' => 'statistical.jv_contract',
//            'guard_name' => "web",
//            'display_name' => "Thống kê theo HĐ LDLK",
//            "group" => "Thống kê",
//            "sidebar_id" => 126,
//        ],
//    ]);
//    //attach permission
//    DB::table("role_has_permissions")->insert([
//        [
//            "permission_id" => 165,
//            "role_id" => 1, //admin
//        ],
//        [
//            "permission_id" => 165,
//            "role_id" => 9, //nvpvt
//        ],
//        [
//            "permission_id" => 165,
//            "role_id" => 13, //tpvt
//        ],
//        [
//            "permission_id" => 165,
//            "role_id" => 18, //ptpvt
//        ],
//        [
//            "permission_id" => 165,
//            "role_id" => 22, //admin_kienan
//        ],
//        [
//            "permission_id" => 165,
//            "role_id" => 23, //nvpvt-ka
//        ],
//    ]);
//});

Route::get("/update-db-30-9-2022", function () {
    DB::table("permissions")->insert([
        [
            'id' => 166,
            'name' => 'external_quality_assessment.read',
            'guard_name' => "web",
            'display_name' => "Ngoại kiểm",
            "group" => "Ngoại kiểm",
            "sidebar_id" => 127,
        ],
    ]);
    //attach permission
    DB::table("role_has_permissions")->insert([
        [
            "permission_id" => 166,
            "role_id" => 1, //admin
        ],
        [
            "permission_id" => 166,
            "role_id" => 9, //nvpvt
        ],
        [
            "permission_id" => 166,
            "role_id" => 13, //tpvt
        ],
        [
            "permission_id" => 166,
            "role_id" => 18, //ptpvt
        ],
        [
            "permission_id" => 166,
            "role_id" => 22, //admin_kienan
        ],
        [
            "permission_id" => 166,
            "role_id" => 23, //nvpvt-ka
        ],
    ]);
});
//Route::get('/change_critical_level', [EquipmentController::class, 'change_critical_level']);
Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::group(['prefix' => 'notification'], function () {
    Route::get('/', [NotificationController::class, 'index'])->name('admin.notification');
    Route::get('/edit/{id}', [NotificationController::class, 'update'])->name('admin.edit');
    Route::post('/delete/{id}', [NotificationController::class, 'destroy'])->name('admin.delete');
    Route::get('/read-all', [NotificationController::class, 'readAll'])->name('admin.readAll');
});

Route::group(['prefix' => 'media'], function () {
    Route::get('/', [MediaAdminController::class, 'index'])->name('mediaAdmin');
    Route::get('/create', [MediaAdminController::class, 'store'])->name('storeMediaAdmin');
    Route::post('/create', [MediaAdminController::class, 'create'])->name('createMediaAdmin');
    Route::get('/edit/{id}', [MediaAdminController::class, 'edit'])->name('editMediaAdmin');
    Route::post('/edit/{id}', [MediaAdminController::class, 'update'])->name('updateMediaAdmin');
    Route::post('/slug/{id}', [MediaAdminController::class, 'changeSlug'])->name('slugMediaAdmin');
    Route::post('/delete/{id}', [MediaAdminController::class, 'delete'])->name('deleteMediaAdmin');
    Route::post('/delete-choose', [MediaAdminController::class, 'deleteChoose'])->name('deleteChooseMediaAdmin');
    Route::post('/popup-media', [MediaAdminController::class, 'loadMediaPopup'])->name('popupMediaAdmin');
    Route::get('/popup-delete-media', [MediaAdminController::class, 'deleteMediaSinglePopup'])->name('popupDeleteMediaSingleAdmin');
    Route::post('/popup-more-media', [MediaAdminController::class, 'loadMorePagePopup'])->name('popupMoreMediaAdmin');
    Route::post('/popup-filter-media', [MediaAdminController::class, 'filterMediaPopup'])->name('popupFilterMediaAdmin');
    Route::post('/popup-search-media-cat', [MediaAdminController::class, 'loadMediaByCatPopup'])->name('popupSearchCatMediaAdmin');
    Route::post('/create-multi', [MediaAdminController::class, 'createMulti'])->name('media.multi_create');
});
Route::group(['prefix' => 'media-cate'], function () {
    Route::get('/', [MediaCatAdminController::class, 'index'])->name('mediaCatAdmin');
    Route::get('/create', [MediaCatAdminController::class, 'store'])->name('storeMediaCatAdmin');
    Route::post('/create', [MediaCatAdminController::class, 'create'])->name('createMediaCatAdmin');
    Route::get('/edit/{id}', [MediaCatAdminController::class, 'edit'])->name('editMediaCatAdmin');
    Route::post('/edit/{id}', [MediaCatAdminController::class, 'update'])->name('updateMediaCatAdmin');
    Route::post('/slug/{id}', [MediaCatAdminController::class, 'changeSlug'])->name('slugMediaCatAdmin');
    Route::post('/delete/{id}', [MediaCatAdminController::class, 'delete'])->name('deleteMediaCatAdmin');
    Route::post('/delete-choose', [MediaCatAdminController::class, 'deleteChoose'])->name('deleteChooseMediaCatAdmin');
});
Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserAdminController::class, 'index'])->name('admin.users');
    Route::get('/create', [UserAdminController::class, 'create'])->name('admin.user_create');
    Route::post('/create', [UserAdminController::class, 'store'])->name('admin.user_store');
    Route::get('/edit/{id}', [UserAdminController::class, 'edit'])->name('admin.user_edit');
    Route::post('/edit/{id}', [UserAdminController::class, 'update'])->name('admin.user_update');
    Route::post('/delete/{id}', [UserAdminController::class, 'delete'])->name('admin.user_delete');
    Route::post('/delete-choose', [UserAdminController::class, 'deleteChoose'])->name('admin.users_delete_choose');
    Route::post('/delete-activity/{id}', [UserAdminController::class, 'delete'])->name('admin.user_delete_activity');
    Route::get('/index-activity', [UserAdminController::class, 'indexActivity'])->name('admin.index_activity');
    Route::post('/index-destroy-activity/{id}', [UserAdminController::class, 'destroyActivity'])->name('admin.destroyActivity');
    Route::post('/delete-choose-activity', [UserAdminController::class, 'deleteChooseActivity'])->name('admin.deleteChooseActivity');
    Route::get('/create-permission/{permission}', [UserAdminController::class, 'createPermission'])->name('admin.permission_create');
    Route::post('/changePass', [UserAdminController::class, 'updatePassword'])->name('admin.updatePassword');
    Route::get('/profile/{id}', [UserAdminController::class, 'yourProfile'])->name('admin.yourProfile');
    Route::post('/profile/{id}', [UserAdminController::class, 'updateProfile'])->name('admin.updateProfile');
});
Route::group(['prefix' => 'system'], function () {
    Route::get('/option', [OptionController::class, 'index'])->name('admin.system');
    Route::post('/option', [OptionController::class, 'update'])->name('admin.system_update');
    Route::get('/config', [OptionController::class, 'config'])->name('admin.config');
    Route::post('/config', [OptionController::class, 'configUpdate'])->name('admin.configUpdate');
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.roles');
        Route::get('/create', [RoleController::class, 'create'])->name('admin.role_create');
        Route::post('/create', [RoleController::class, 'store'])->name('admin.role_store');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('admin.role_edit');
        Route::post('/edit/{id}', [RoleController::class, 'update'])->name('admin.role_update');
        Route::post('/delete/{id}', [RoleController::class, 'delete'])->name('admin.role_delete');
        Route::post('/delete-choose', [RoleController::class, 'deleteChoose'])->name('admin.roles_delete_choose');
    });
});
Route::group(['prefix' => 'department'], function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('department.index');
    Route::post('/create', [DepartmentController::class, 'store'])->name('department.post');
    Route::get('/create', [DepartmentController::class, 'create'])->name('department.create');
    Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
    Route::put('/edit/{id}', [DepartmentController::class, 'update'])->name('department.put');
    Route::post('/delete/{id}', [DepartmentController::class, 'destroy'])->name('department.delete');
    Route::post('/delete-choose', [DepartmentController::class, 'deleteChoose'])->name('department.deleteChoosePageAdmin');
});
Route::group(['prefix' => 'provider'], function () {
    Route::get('/', [ProviderController::class, 'index'])->name('provider.index');
    Route::post('/create', [ProviderController::class, 'store'])->name('provider.post');
    Route::get('/create', [ProviderController::class, 'create'])->name('provider.create');
    Route::get('/edit/{id}', [ProviderController::class, 'edit'])->name('provider.edit');
    Route::put('/edit/{id}', [ProviderController::class, 'update'])->name('provider.put');
    Route::post('/delete/{id}', [ProviderController::class, 'destroy'])->name('provider.delete');
});
Route::group(['prefix' => 'maintenance'], function () {
    Route::get('/', [ProviderController::class, 'indexMaintenance'])->name('maintenance.index');
    Route::post('/create', [ProviderController::class, 'storeMaintenance'])->name('maintenance.post');
    Route::get('/create', [ProviderController::class, 'createMaintenance'])->name('maintenance.create');
    Route::get('/edit/{id}', [ProviderController::class, 'editMaintenance'])->name('maintenance.edit');
    Route::put('/edit/{id}', [ProviderController::class, 'updateMaintenance'])->name('maintenance.put');
    Route::post('/delete/{id}', [ProviderController::class, 'destroyMaintenance'])->name('maintenance.delete');
});
Route::group(['prefix' => 'repair'], function () {
    Route::get('/', [ProviderController::class, 'indexRepair'])->name('repair.index');
    Route::post('/create', [ProviderController::class, 'storeRepair'])->name('repair.post');
    Route::get('/create', [ProviderController::class, 'createRepair'])->name('repair.create');
    Route::get('/edit/{id}', [ProviderController::class, 'editRepair'])->name('repair.edit');
    Route::put('/edit/{id}', [ProviderController::class, 'updateRepair'])->name('repair.put');
    Route::post('/delete/{id}', [ProviderController::class, 'destroyRepair'])->name('repair.delete');
    Route::post('/delete-choose', [ProviderController::class, 'deleteChoose'])->name('repair.deleteChoosePageAdmin');
});
Route::group(['prefix' => 'cates'], function () {
    Route::get('/', [CatesController::class, 'index'])->name('equipment_cate.index');
    Route::post('/create', [CatesController::class, 'store'])->name('equipment_cate.post');
    Route::get('/create', [CatesController::class, 'create'])->name('equipment_cate.create');
    Route::get('/edit/{id}', [CatesController::class, 'edit'])->name('equipment_cate.edit');
    Route::put('/edit/{id}', [CatesController::class, 'update'])->name('equipment_cate.put');
    Route::post('/delete/{id}', [CatesController::class, 'destroy'])->name('equipment_cate.delete');
    Route::post('/delete-choose', [CatesController::class, 'deleteChoose'])->name('cates.deleteChoosePageAdmin');
});
Route::group(['prefix' => 'device'], function () {
    Route::get('/', [DeviceController::class, 'index'])->name('type_device.index');
    Route::post('/create', [DeviceController::class, 'store'])->name('type_device.post');
    Route::get('/create', [DeviceController::class, 'create'])->name('type_device.create');
    Route::get('/edit/{id}', [DeviceController::class, 'edit'])->name('type_device.edit');
    Route::put('/edit/{id}', [DeviceController::class, 'update'])->name('type_device.put');
    Route::post('/delete/{id}', [DeviceController::class, 'destroy'])->name('type_device.delete');
    Route::post('/delete-choose', [DeviceController::class, 'deleteChoose'])->name('device.deleteChoosePageAdmin');
});
Route::group(['prefix' => 'supplie'], function () {
    Route::get('/', [SupplieController::class, 'index'])->name('supplie.index');
    Route::post('/create', [SupplieController::class, 'store'])->name('supplie.post');
    Route::get('/create', [SupplieController::class, 'create'])->name('supplie.create');
    Route::get('/edit/{id}', [SupplieController::class, 'edit'])->name('supplie.edit');
    Route::put('/edit/{id}', [SupplieController::class, 'update'])->name('supplie.put');
    Route::post('/delete/{id}', [SupplieController::class, 'destroy'])->name('supplie.delete');
    Route::post('/delete-choose', [SupplieController::class, 'deleteChoose'])->name('supplie.deleteChoosePageAdmin');
});
Route::group(['prefix' => 'unit'], function () {
    Route::get('/', [UnitController::class, 'index'])->name('unit.index');
    Route::post('/create', [UnitController::class, 'store'])->name('unit.post');
    Route::get('/create', [UnitController::class, 'create'])->name('unit.create');
    Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::put('/edit/{id}', [UnitController::class, 'update'])->name('unit.put');
    Route::post('/delete/{id}', [UnitController::class, 'destroy'])->name('unit.delete');
    Route::post('/delete-choose', [UnitController::class, 'deleteChoose'])->name('unit.deleteChoosePageAdmin');
});
Route::group(['prefix' => 'project'], function () {
    Route::get('/', [ProjectController::class, 'index'])->name('project.index');
    Route::post('/create', [ProjectController::class, 'store'])->name('project.post');
    Route::get('/create', [ProjectController::class, 'create'])->name('project.create');
    Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('project.edit');
    Route::put('/edit/{id}', [ProjectController::class, 'update'])->name('project.put');
    Route::post('/delete/{id}', [ProjectController::class, 'destroy'])->name('project.delete');
    Route::post('/delete-choose', [ProjectController::class, 'deleteChoose'])->name('project.deleteChoosePageAdmin');
});
Route::group(['prefix' => 'equipment'], function () {
    //Route::get('/change_critical_level', [EquipmentController::class, 'change_critical_level']);
    Route::get('/device', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('/mediacal', [EquipmentController::class, 'indexMedical'])->name('equipment.indexMedical');
    Route::get('/listImports', [EquipmentController::class, 'listImport'])->name('equipment.listimport');
    Route::get('/courses', [EquipmentController::class, 'courses'])->name('equipment.courses');
    Route::get('/show/{id}', [EquipmentController::class, 'show'])->name('equipment.show');
    Route::get('/hand_over/{id}', [EquipmentController::class, 'hand_over'])->name('equipment.hand_over');
    Route::post('/create', [EquipmentController::class, 'store'])->name('equipment.post');
    Route::post('/create-supplie', [EquipmentController::class, 'storeSupplie'])->name('equipment_supplie.post');
    Route::get('/create-view', [EquipmentController::class, 'create'])->name('equipment.create');
    Route::get('/create-supplie/{id}', [EquipmentController::class, 'createSupplie'])->name('equipment.createSupplie');
    Route::get('/history', [EquipmentController::class, 'showHistory'])->name('equipment.history');
    Route::get('/edit/{id}', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::get('/export', [EquipmentController::class, 'export'])->name('equipment.export');
    Route::post('/import', [EquipmentController::class, 'import'])->name('equipment.import');
    Route::post('/updateHashCodeImport', [EquipmentController::class, 'updateHashCodeImport'])->name('equipment.updateEquipmentImport');

    Route::put('/edit/{id}', [EquipmentController::class, 'update'])->name('equipment.put');
    Route::put('/edit-hand-over/{id}', [EquipmentController::class, 'updateHandOver'])->name('equipment.updateHandOver');
    Route::put('/edit-corrected/{id}', [EquipmentController::class, 'updateCorrected'])->name('equipment.updateCorrected');
    Route::put('/edit-inactive/{id}', [EquipmentController::class, 'updateInactive'])->name('equipment.updateInactive');
    Route::put('/edit-was-broken/{id}', [EquipmentController::class, 'updateWasBroken'])->name('equipment.updateWasBroken');
    Route::put('/edit-was-broken-device/{id}', [EquipmentController::class, 'updateWasBrokenDevice'])->name('equipment.updateWasBrokenDevice');
    Route::post('/delete/{id}', [EquipmentController::class, 'destroy'])->name('equipment.delete');
    Route::post('/deleteHistory/{id}', [EquipmentController::class, 'destroyHistory'])->name('equipment.destroyHistory');
    Route::post('/select', [EquipmentController::class, 'select'])->name('equiment.select');
    Route::post('/select-hand-over', [EquipmentController::class, 'selectHandOver'])->name('equiment.selectHandOver');
    Route::post('/delete-choose-history', [EquipmentController::class, 'deleteChooseHistory'])->name('equipment.deleteChooseHistory');
    Route::get('/pdf/{id}', [EquipmentController::class, 'showPdf'])->name('equipment.showPdf');

    // Lịch bảo trì, bảo dưỡng
    Route::group(['prefix' => 'maintenances'], function () {
        Route::get('/', [MaintenanceController::class, 'index'])->name('equip_maintenance.index');
        Route::get('/export-mainte', [MaintenanceController::class, 'exportEquipMainte'])->name('equip_maintenance.exportEquipMainte');
        Route::group(['prefix' => 'equip-{equip_id}'], function () {
            Route::get('/create', [MaintenanceController::class, 'create'])->name('equip_maintenance.create');
            Route::post('/create', [MaintenanceController::class, 'store'])->name('equip_maintenance.store');
            Route::get('/showHistories', [MaintenanceController::class, 'showHistories'])->name('equip_maintenance.showHistories');
            Route::get('/edit/{main_id}', [MaintenanceController::class, 'edit'])->name('equip_maintenance.edit');
            Route::post('/edit/{main_id}', [MaintenanceController::class, 'update'])->name('equip_maintenance.update');
            Route::post('/delete/{main_id}', [MaintenanceController::class, 'destroy'])->name('equip_maintenance.delete');
            // Route::get('/histories',[MaintenanceActionController::class, 'index'])->name('equip_maintenance.history');
            // Route::group(['prefix'=>'action-{main_id}'],function(){
            // 	Route::post('/create',[MaintenanceActionController::class, 'store'])->name('maintenance_act.store');
            // 	Route::post('/edit/{id}',[MaintenanceActionController::class, 'edit'])->name('maintenance_act.edit');
            // 	Route::post('/update/{id}',[MaintenanceActionController::class, 'update'])->name('maintenance_act.update');
            // });
        });
    });
});
Route::group(['prefix' => 'eqproperty'], function () {
    Route::get('/device', [EqpropertyController::class, 'index'])->name('eqproperty.index');
    Route::get('/listImports', [EqpropertyController::class, 'listImport'])->name('eqproperty.listimport');
    Route::get('/courses', [EqpropertyController::class, 'courses'])->name('eqproperty.courses');
    Route::get('/show/{id}', [EqpropertyController::class, 'show'])->name('eqproperty.show');
    Route::get('/hand_over/{id}', [EqpropertyController::class, 'hand_over'])->name('eqproperty.hand_over');
    Route::post('/create', [EqpropertyController::class, 'store'])->name('eqproperty.post');
    Route::get('/create-view', [EqpropertyController::class, 'create'])->name('eqproperty.create');
    Route::get('/create-supplie/{id}', [EqpropertyController::class, 'createSupplie'])->name('eqproperty.createSupplie');
    Route::get('/history', [EqpropertyController::class, 'showHistory'])->name('eqproperty.history');
    Route::get('/edit/{id}', [EqpropertyController::class, 'edit'])->name('eqproperty.edit');
    Route::get('/export', [EqpropertyController::class, 'export'])->name('eqproperty.export');
    Route::post('/import', [EqpropertyController::class, 'import'])->name('eqproperty.import');
    Route::post('/updateHashCodeImport', [EqpropertyController::class, 'updateHashCodeImport'])->name('eqproperty.updateEquipmentImport');
    Route::get('/check1', [EqpropertyController::class, 'getUserForSend']);

    Route::put('/edit/{id}', [EqpropertyController::class, 'update'])->name('eqproperty.put');
    Route::put('/edit-hand-over/{id}', [EqpropertyController::class, 'updateHandOver'])->name('eqproperty.updateHandOver');
    Route::put('/edit-corrected/{id}', [EqpropertyController::class, 'updateCorrected'])->name('eqproperty.updateCorrected');
    Route::put('/edit-inactive/{id}', [EqpropertyController::class, 'updateInactive'])->name('eqproperty.updateInactive');
    Route::put('/edit-was-broken/{id}', [EqpropertyController::class, 'updateWasBroken'])->name('eqproperty.updateWasBroken');
    Route::put('/edit-was-broken-device/{id}', [EqpropertyController::class, 'updateWasBrokenDevice'])->name('eqproperty.updateWasBrokenDevice');
    Route::post('/delete/{id}', [EqpropertyController::class, 'destroy'])->name('eqproperty.delete');
    Route::post('/deleteHistory/{id}', [EqpropertyController::class, 'destroyHistory'])->name('eqproperty.destroyHistory');
    Route::post('/select', [EqpropertyController::class, 'select'])->name('eqproperty.select');
    Route::post('/select-hand-over', [EqpropertyController::class, 'selectHandOver'])->name('eqproperty.selectHandOver');
    Route::post('/delete-choose-history', [EqpropertyController::class, 'deleteChooseHistory'])->name('eqproperty.deleteChooseHistory');
    Route::get('/pdf/{id}', [EqpropertyController::class, 'showPdf'])->name('eqproperty.showPdf');
});
Route::group(['prefix' => 'eqsupplie'], function () {
    Route::get('/index', [EqsupplieController::class, 'index'])->name('eqsupplie.index');
    Route::get('/list-import', [EqsupplieController::class, 'listImport'])->name('eqsupplie.listimport');
    Route::get('/show/{id}', [EqsupplieController::class, 'show'])->name('eqsupplie.show');
    Route::post('/create', [EqsupplieController::class, 'store'])->name('eqsupplie.post');
    Route::post('/create-compatible/{id}', [EqsupplieController::class, 'storeCompatible'])->name('eqsupplie.storeCompatible');
    Route::get('/create', [EqsupplieController::class, 'create'])->name('eqsupplie.create');
    Route::get('/edit/{id}', [EqsupplieController::class, 'edit'])->name('eqsupplie.edit');
    Route::get('/compatible/{id}', [EqsupplieController::class, 'showCompatible'])->name('eqsupplie.showCompatible');
    Route::get('/export', [EqsupplieController::class, 'export'])->name('eqsupplie.export');
    Route::put('/edit/{id}', [EqsupplieController::class, 'update'])->name('eqsupplie.put');
    Route::put('/amount/{id}', [EqsupplieController::class, 'updateAmount'])->name('eqsupplie_amount.put');
    Route::put('/used/{id}-{equip_id}', [EqsupplieController::class, 'updateUsed'])->name('eqsupplieUsed.put');
    Route::post('/import', [EqsupplieController::class, 'import'])->name('eqsupplie.import');
    Route::post('/delete/{id}', [EqsupplieController::class, 'destroy'])->name('eqsupplie.delete');
    Route::post('/delete-compatible/{id}', [EqsupplieController::class, 'destroyCompatible'])->name('eqsupplie.deleteCompatible');
    Route::post('/amount-department', [EqsupplieController::class, 'showAmountDepartment'])->name('eqsupplie.showAmountDepartment');
});
// Bảo hành
Route::group(['prefix' => 'guarantee'], function () {
    Route::get('/', [GuaranteeController::class, 'index'])->name('guarantee.index');
    Route::get('/export-guara', [GuaranteeController::class, 'exportEquipGuara'])->name('guarantee.exportEquipGuara');
    Route::post('/create/{id}', [GuaranteeController::class, 'store'])->name('guarantee.post');
    Route::get('/create', [GuaranteeController::class, 'create'])->name('guarantee.create');
    Route::get('/edit/{id}', [GuaranteeController::class, 'edit'])->name('guarantee.edit');
    Route::put('/edit/{id}', [GuaranteeController::class, 'update'])->name('guarantee.put');
    Route::post('/delete/{id}', [GuaranteeController::class, 'destroy'])->name('guarantee.delete');
});
// Kiểm định
Route::group(['prefix' => 'accre'], function () {
    Route::get('/', [AccreController::class, 'index'])->name('accre.index');
    Route::get('/export-inspec', [AccreController::class, 'exportEquipInspec'])->name('accre.exportEquipInspec');
    Route::get('/export-inspec-next-month', [AccreController::class, 'exportEquipInspecNextMonth'])->name('accre.exportEquipInspecNextMonth');
    Route::post('/create/{id}', [AccreController::class, 'store'])->name('accre.post');
    Route::get('/create', [AccreController::class, 'create'])->name('accre.create');
    Route::get('/edit/{id}', [AccreController::class, 'edit'])->name('accre.edit');
    Route::put('/edit/{id}', [AccreController::class, 'update'])->name('accre.put');
    Route::post('/delete/{id}', [AccreController::class, 'destroy'])->name('accre.delete');
});
// kiểm xạ
Route::prefix("radiation_inspection")->name("radiation_inspection.")
    ->controller(RadiationInspectionController::class)
    ->group(function () {
        Route::get("/", "index")->name("index");
        Route::get("/history/{equipment}", "showHistory")->name("history");
        Route::post("/store/{equipment}", "store")->name("store");
        Route::post("/update-history/{radiationInspection}", "updateHistory")->name("updateHistory");
        Route::get("/export-radiation_inspection", "exportRadiationInspection")->name("exportRadiationInspection");
        Route::get("/export-radiation_inspection-next-month", "exportRadiationInspectionNextMonth")->name("exportRadiationInspectionNextMonth");
    });
Route::prefix("external_quality_assessment")->name("external_quality_assessment.")
    ->controller(ExternalQualityAssessmentController::class)
    ->group(function () {
        Route::get("/", "index")->name("index");
        Route::get("/history/{equipment}", "showHistory")->name("history");
        Route::post("/store/{equipment}", "store")->name("store");
        Route::post("/update-history/{radiationInspection}", "updateHistory")->name("updateHistory");
        Route::get("/export", "export")->name("export");
        Route::get("/export-next-month", "exportNextMonth")->name("exportNextMonth");
    });
Route::prefix("clinic_environment_inspection")->name("clinic_environment_inspection.")
    ->controller(ClinicEnvironmentInspectionController::class)
    ->group(function () {
        Route::get("/", "index")->name("index");
        Route::get("/history/{equipment}", "showHistory")->name("history");
        Route::post("/store/{equipment}", "store")->name("store");
        Route::post("/update-history/{radiationInspection}", "updateHistory")->name("updateHistory");
        Route::get("/export", "export")->name("export");
        Route::get("/export-next-month", "exportNextMonth")->name("exportNextMonth");
    });
Route::prefix("license_renewal_of_radiation_work")->name("license_renewal_of_radiation_work.")
    ->controller(LicenseRenewalOfRadiationWorkController::class)
    ->group(function () {
        Route::get("/", "index")->name("index");
        Route::get("/history/{equipment}", "showHistory")->name("history");
        Route::post("/store/{equipment}", "store")->name("store");
        Route::post("/update-history/{radiationInspection}", "updateHistory")->name("updateHistory");
        Route::get("/export", "export")->name("export");
        Route::get("/export-next-month", "exportNextMonth")->name("exportNextMonth");
    });
// phiếu nhập
Route::group(['prefix' => 'ballot'], function () {
    Route::get('/', [EquipmentBallotController::class, 'index'])->name('ballot.index');
    Route::post('/create', [EquipmentBallotController::class, 'store'])->name('ballot.post');
    Route::post('/table', [EquipmentBallotController::class, 'table'])->name('ballot.table');
    Route::get('/create', [EquipmentBallotController::class, 'create'])->name('ballot.create');
    Route::get('/edit/{id}', [EquipmentBallotController::class, 'edit'])->name('ballot.edit');
    Route::put('/edit/{id}', [EquipmentBallotController::class, 'update'])->name('ballot.put');
    Route::put('/edit-success/{id}', [EquipmentBallotController::class, 'updateSuccess'])->name('ballot.updateSuccess');
    Route::post('/delete/{id}', [EquipmentBallotController::class, 'destroy'])->name('ballot.delete');
    Route::post('/delete-eq/{id}', [EquipmentBallotController::class, 'destroyEq'])->name('ballot.deleteEq');
    Route::post('/requi', [EquipmentBallotController::class, 'showEqui'])->name('ballot.showEqui');
});
// phiếu nhập vật tư
Route::group(['prefix' => 'supplie-ballot'], function () {
    Route::get('/', [SupplieBallotController::class, 'index'])->name('supplieBallot.index');
    Route::post('/create', [SupplieBallotController::class, 'store'])->name('supplieBallot.post');
    Route::post('/table', [SupplieBallotController::class, 'table'])->name('supplieBallot.table');
    Route::get('/create', [SupplieBallotController::class, 'create'])->name('supplieBallot.create');
    Route::get('/edit/{id}', [SupplieBallotController::class, 'edit'])->name('supplieBallot.edit');
    Route::put('/edit/{id}', [SupplieBallotController::class, 'update'])->name('supplieBallot.put');
    Route::put('/edit-success/{id}', [SupplieBallotController::class, 'updateSuccess'])->name('supplieBallot.updateSuccess');
    Route::post('/delete/{id}', [SupplieBallotController::class, 'destroy'])->name('supplieBallot.delete');
    Route::post('/delete-eq/{id}', [SupplieBallotController::class, 'destroyEq'])->name('supplieBallot.deleteEq');
    Route::post('/requi', [SupplieBallotController::class, 'showEqui'])->name('supplieBallot.showEqui');
});
// bảo dưỡng định kỳ
Route::group(['prefix' => 'periodic'], function () {
    Route::get('/', [ActionController::class, 'indexPeriodic'])->name('periodic.index');
    Route::post('/create', [ActionController::class, 'storePeriodic'])->name('periodic.post');
    Route::get('/create', [ActionController::class, 'createPeriodic'])->name('periodic.create');
    Route::get('/edit/{id}', [ActionController::class, 'editPeriodic'])->name('periodic.edit');
    Route::put('/edit/{id}', [ActionController::class, 'updatePeriodic'])->name('periodic.put');
    Route::post('/delete/{id}', [ActionController::class, 'destroyPeriodic'])->name('periodic.delete');
    Route::post('/delete-choose', [ProjectController::class, 'deleteChoose'])->name('periodic.deleteChoosePageAdmin');
});
// điều chuyển thiết bị
Route::group(['prefix' => 'transfer'], function () {
    Route::get('/', [TransferController::class, 'index'])->name('transfer.index');
    Route::get('/pdf/{id}', [TransferController::class, 'showPdf'])->name('transfer.showPdf');
    Route::get('/pdf', [TransferController::class, 'pdf'])->name('transfer.pdf');
    Route::get('/transfer-supplie/{id}', [TransferController::class, 'transferSupplie'])->name('transfer.supplie');
    Route::post('/getQuantity', [TransferController::class, 'getQuantity'])->name('transfer.getQuantity');
    Route::get('/word-export/{id}', [TransferController::class, 'wordExport'])->name('transfer.wordExport');
    Route::post('/create', [TransferController::class, 'store'])->name('transfer.post');
    Route::get('/create', [TransferController::class, 'create'])->name('transfer.create');
    Route::get('/edit/{id}-{supplies_id}', [TransferController::class, 'edit'])->name('transfer.edit');
    Route::put('/update/{id}', [TransferController::class, 'update'])->name('transfer.put');
    Route::post('/delete/{id}', [TransferController::class, 'destroy'])->name('transfer.delete');
});
// thống kê
Route::group(['prefix' => 'statistical'], function () {
    Route::get('/info-equip', [StatisticController::class, 'infoEquip'])->name('statistical.infoEquip');
    Route::get('/export-info', [StatisticController::class, 'exportInfo'])->name('statistical.exportInfo');
    Route::get('/departments', [StatisticController::class, 'departments'])->name('statistical.departments');
    Route::get('/export-departments', [StatisticController::class, 'exportDepartments'])->name('statistical.exportDepartments');
    Route::get('/classify', [StatisticController::class, 'classify'])->name('statistical.classify');
    Route::get('/export-group', [StatisticController::class, 'exportGroups'])->name('statistical.exportGroups');
    Route::get('/export-type', [StatisticController::class, 'exportTypes'])->name('statistical.exportTypes');
    Route::get('/export-status', [StatisticController::class, 'exportStatus'])->name('statistical.exportStatus');
    Route::get('/year-manufacture', [StatisticController::class, 'yearManufacture'])->name('statistical.yearManufacture');
    Route::get('/export-year-use', [StatisticController::class, 'exportYearUse'])->name('statistical.exportYearUse');
    Route::get('/supplies', [StatisticController::class, 'supplies'])->name('statistical.supplies');
    Route::get('/export-supplies', [StatisticController::class, 'exportSupplies'])->name('statistical.exportSupplies');
    Route::get('/risk', [StatisticController::class, 'risk'])->name('statistical.risk');
    Route::get('/export-risk', [StatisticController::class, 'exportRisk'])->name('statistical.exportRisk');
    Route::get('/project', [StatisticController::class, 'project'])->name('statistical.project');
    Route::get('/export-project', [StatisticController::class, 'exportProject'])->name('statistical.exportProject');
    Route::get('/accreditation', [StatisticController::class, 'accreditation'])->name('statistical.accreditation');
    Route::get('/export-accreditation', [StatisticController::class, 'exportAccreditation'])->name('statistical.exportAccreditation');
    Route::get('/jv-contract', [StatisticController::class, 'jvContract'])->name('statistical.jvContract');
    Route::get('/jv-contract-export', [StatisticController::class, 'jvContractExport'])->name('statistical.jvContractExport');
    Route::get('/warranty-date', [StatisticController::class, 'warrantyDate'])->name('statistical.warrantyDate');
    Route::get('/export-warranty-date', [StatisticController::class, 'exportWarrantyDate'])->name('statistical.exportWarrantyDate');
});
Route::group(['prefix' => 'request'], function () {
    Route::get('/', [RequestController::class, 'index'])->name('request.index');
    Route::post('/create', [RequestController::class, 'store'])->name('request.post');
    Route::get('/create', [RequestController::class, 'create'])->name('request.create');
    Route::get('/edit/{id}', [RequestController::class, 'edit'])->name('request.edit');
    Route::post('/edit/{id}', [RequestController::class, 'update'])->name('request.put');
    Route::post('/delete/{id}', [RequestController::class, 'destroy'])->name('request.delete');
});
//bảng kê
Route::group(['prefix' => 'general'], function () {
    Route::get('/input-department', [GeneralController::class, 'inputDepartment'])->name('general.inputDepartment');
    Route::get('/export-input-department', [GeneralController::class, 'exportInputDepartment'])->name('general.exportInputDepartment');
    Route::get('/input-supplies', [GeneralController::class, 'inputSupplies'])->name('general.inputSupplies');
    Route::get('/export-input-supplie', [GeneralController::class, 'exportInputSupplie'])->name('general.exportInputSupplie');
    Route::get('/schedule-repair', [GeneralController::class, 'scheduleRepairs'])->name('general.scheduleRepairs');
    Route::get('/export-schedule-repairs', [GeneralController::class, 'exportScheduleRepairs'])->name('general.exportScheduleRepairs');
    Route::get('/liquidations', [GeneralController::class, 'Liquidations'])->name('general.liquidations');
    Route::get('/export-liquidations', [GeneralController::class, 'exportLiquidations'])->name('general.exportLiquidations');
    Route::get('/supplie-department', [GeneralController::class, 'suppliesDepartment'])->name('general.supplieDepartment');
    Route::get('/export-supplie-department', [GeneralController::class, 'exportSupplieDepartment'])->name('general.exportSupplieDepartment');
    Route::get('/transfer-equipment', [GeneralController::class, 'transferEquipment'])->name('general.transferEquipment');
    Route::get('/export-transfer-equipment', [GeneralController::class, 'exportTransferEquipment'])->name('general.exportTransferEquipment');
    Route::get('/maintenance-equipment', [GeneralController::class, 'maintenanceEquipment'])->name('general.maintenanceEquipment');
    Route::get('/export-maintenance-equipment', [GeneralController::class, 'exportMaintenanceEquipment'])->name('general.exportMaintenanceEquipment');
});
// báo hỏng và sửa sữa thiet bi
Route::group(['prefix' => 'eqrepair'], function () {
    Route::get('/', [EqRepairController::class, 'index'])->name('eqrepair.index');
    Route::group(['prefix' => 'equip-{equip_id}'], function () {
        Route::get('/create', [EqRepairController::class, 'create'])->name('eqrepair.create');
        Route::post('/create', [EqRepairController::class, 'store'])->name('eqrepair.store');
        Route::get('/list-rp', [EqRepairController::class, 'listRepair'])->name('eqrepair.history');
        Route::get('/list-rp-export', [EqRepairController::class, 'listRepairExport'])->name('eqrepair.historyExport');
        Route::post('/state-transition', [EqRepairController::class, 'stateTransition'])->name('eqrepair.stateTransition');
        Route::get('/export-word', [EqRepairController::class, 'exportWord'])->name('eqrepair.exportWord');
        Route::group(['prefix' => 'repair-{repair_id}'], function () {
            Route::get('/edit', [EqRepairController::class, 'edit'])->name('eqrepair.edit');
            Route::post('/edit', [EqRepairController::class, 'update'])->name('eqrepair.update');
            Route::post('/delete', [EqRepairController::class, 'destroy'])->name('eqrepair.delete');
        });
    });
});
//báo hỏng và sửa tsc
Route::group(['prefix' => 'prorepair'], function () {
    Route::get('/', [ProRepairController::class, 'index'])->name('prorepair.index');
    Route::group(['prefix' => 'equip-{equip_id}'], function () {
        Route::get('/create', [ProRepairController::class, 'create'])->name('prorepair.create');
        Route::post('/create', [ProRepairController::class, 'store'])->name('prorepair.store');
        Route::get('/list-rp', [ProRepairController::class, 'listRepair'])->name('prorepair.history');
        Route::get('/list-rp-export', [ProRepairController::class, 'listRepairExport'])->name('prorepair.historyExport');
        Route::post('/state-transition', [ProRepairController::class, 'stateTransition'])->name('prorepair.stateTransition');
        Route::get('/export-word', [ProRepairController::class, 'exportWord'])->name('prorepair.exportWord');
        Route::group(['prefix' => 'repair-{repair_id}'], function () {
            Route::get('/edit', [ProRepairController::class, 'edit'])->name('prorepair.edit');
            Route::post('/edit', [ProRepairController::class, 'update'])->name('prorepair.update');
            Route::post('/delete', [ProRepairController::class, 'destroy'])->name('prorepair.delete');
        });
    });
});
// ngừng sử dụng
Route::group(['prefix' => 'eqliquis'], function () {
    Route::get('/', [LiquidationController::class, 'index'])->name('eqliquis.index');
    Route::get('/export-liquidation', [LiquidationController::class, 'exportLiquidation'])->name('general.exportLiquidation');
    Route::group(['prefix' => 'equip-{equip_id}'], function () {
        Route::post('/create', [LiquidationController::class, 'store'])->name('eqliquis.store');
        Route::get('/list-lq', [LiquidationController::class, 'listLiqui'])->name('eqliquis.listLiqui');
        Route::group(['prefix' => 'liqui-{liqui_id}'], function () {
            Route::post('/edit', [LiquidationController::class, 'update'])->name('eqliquis.update');
            Route::post('/delete', [LiquidationController::class, 'destroy'])->name('eqliquis.delete');
        });
    });
});
// ngừng sử dụng tai san cong
Route::group(['prefix' => 'proliquis'], function () {
    Route::get('/', [ProLiquidController::class, 'index'])->name('proliquis.index');
    Route::get('/export-liquidation', [ProLiquidController::class, 'exportLiquidation'])->name('general.exportLiquidation');
    Route::group(['prefix' => 'equip-{equip_id}'], function () {
        Route::post('/create', [ProLiquidController::class, 'store'])->name('proliquis.store');
        Route::get('/list-lq', [ProLiquidController::class, 'listLiqui'])->name('proliquis.listLiqui');
        Route::group(['prefix' => 'liqui-{liqui_id}'], function () {
            Route::post('/edit', [ProLiquidController::class, 'update'])->name('proliquis.update');
            Route::post('/delete', [ProLiquidController::class, 'destroy'])->name('proliquis.delete');
        });
    });
});
// kiểm kê
Route::group(['prefix' => 'inventory'], function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::group(['prefix' => 'depart-{depart_id}'], function () {
        Route::get('/list-equipment', [InventoryController::class, 'listEquipment'])->name('inventory.listEquipment');
        Route::post('/reset', [InventoryController::class, 'resetInventory'])->name('inventory.resetInventory');
        Route::get('/completed-inventory', [InventoryController::class, 'completedInventory'])->name('inventory.completedInventory');
        Route::post('/browser', [InventoryController::class, 'browserInventory'])->name('inventory.browserInventory');
        Route::get('/export-equipment', [InventoryController::class, 'exportEquipment'])->name('inventory.exportEquipment');
        Route::get('/history-inventory', [InventoryController::class, 'historyInventory'])->name('inventory.historyInventory');
    });
    Route::group(['prefix' => 'equip-{equip_id}'], function () {
        Route::get('/create', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/create', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/list-inventory', [InventoryController::class, 'listInventory'])->name('inventory.listInventory');
        Route::group(['prefix' => 'inven-{inven_id}'], function () {
            Route::post('/delete', [InventoryController::class, 'destroy'])->name('inventory.delete');
        });
    });
});
// kiểm kê vật tư
Route::group(['prefix' => 'inventorysup'], function () {
    Route::get('/', [InventorySupController::class, 'index'])->name('inventorysup.index');
    Route::group(['prefix' => 'depart-{depart_id}'], function () {
        Route::get('/list-supplie', [InventorySupController::class, 'listSupplies'])->name('inventorysup.listSupplies');
        Route::post('/reset', [InventorySupController::class, 'resetInventory'])->name('inventorysup.resetInventory');
        Route::get('/completed-inventory', [InventorySupController::class, 'completedInventory'])->name('inventorysup.completedInventory');
        Route::post('/browser', [InventorySupController::class, 'browserInventory'])->name('inventorysup.browserInventory');
        Route::get('/export-equipment', [InventorySupController::class, 'exportEquipment'])->name('inventorysup.exportEquipment');
    });
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Application cache flushed";
});
Route::get('/clear-route-cache', function () {
    Artisan::call('route:clear');
    return "Route cache file removed";
});
Route::get('/clear-config-cache', function () {
    Artisan::call('config:clear');
    return "Configuration cache file removed";
});
Route::get('/tesss', function () {
    Artisan::call('optimize');
    return "optimize file removed";
});
Route::get('/updateapp', function () {
    system('composer dump-autoload');
    echo 'dump-autoload completed';
});
Route::get('/automail', function () {
    Artisan::call('accreMail:send');
    return "test mail";
});
//Route::get('/automail',[AccreController::class, 'handle']);
// Route::get('/testscheduledtask', function(Schedule  $schedule){
//     ->everyMinutes();
// });
