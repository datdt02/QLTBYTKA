<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\EqpropertyController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\StatisticalController;
use App\Http\Controllers\Api\UserController;
use App\Models\Equipment;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Login

Route::get('/getImg/{id}', function ($id) {
    $idImg = Equipment::findOrFail($id)->image;
    $pathImg = Media::findOrFail($idImg);
    return $pathImg->getLink();
});

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {

    Route::group(['prefix' => 'v1'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // User
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);


        // Departments
        Route::get('/departments', [DepartmentController::class, 'index']);
        Route::get('/departments/{id}', [DepartmentController::class, 'show']);

        //Equipments
        Route::get('/equipments', [EquipmentController::class, 'index']);
        Route::get('/equipments/{id}', [EquipmentController::class, 'show']);
        Route::post('/equipment/{id}', [EquipmentController::class, 'updateWasBroken']);

        //Equipments
        Route::get('/eqproperties', [EqpropertyController::class, 'indexV2']);
        Route::get('/eqproperties/{id}', [EqpropertyController::class, 'show']);
        Route::post('/eqproperty/{id}', [EqpropertyController::class, 'updateWasBroken']);

        //Inventory
        Route::get('/listEquipmentInventory/{depart_id}', [InventoryController::class, 'listEquipment']);
        Route::post('/createInventory/{id}', [InventoryController::class, 'createInventory']);
        Route::get('/listInventoryByEquipmentID/{id}', [InventoryController::class, 'listInventoryByEquipmentID']);

        Route::get('/notification', function () {
            $user = Auth::user();
            $notif = $user->notifications()->get();
            return response()->json([
                'status' => 200,
                'data' => $notif,
                'total' => $notif->count()
            ]);
        });
        //statistical
        Route::get('/statistical-by-info', [StatisticalController::class, 'statisticByInfo']);
        Route::get('/count-broken-and-repairing-equipment-each-department', [StatisticalController::class, 'countBrokenAndRepairingEquipmentEachDepartment']);
    });
    Route::group(['prefix' => 'v2'], function () {
        Route::get('/equipments', [EquipmentController::class, 'indexV2']);
    });
});
