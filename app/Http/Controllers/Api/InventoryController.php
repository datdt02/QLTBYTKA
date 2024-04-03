<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateInventoryRequest;
use Exception;
use Carbon\Carbon;
use App\Models\Equipment;
use App\Models\Inventory;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\HistoryInventories;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function listEquipment($depart_id){
        try{
            $equipments = Equipment::where('department_id',$depart_id)
            ->select('equipments.id','equipments.title', 'equipments.model', 'equipments.serial')
            ->get();
            foreach( $equipments as $item ){

                $ab = Inventory::where('equipment_id',$item->id)
                ->orderBy('date', 'desc')
                ->select('note','date','equipment_id')
                ->first();
                $item['inventories'] = $ab != null ? $ab : null;

            }

            return response()->json([
                'status_code' => 200,
                'data' => $equipments,
                'dataLength' => $equipments->count(),
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status_code' => 500,
                'message' => 'Server not responding',
                'error' => $e,
            ],500);
        }
    }

    public function createInventory(CreateInventoryRequest $request, $equip_id){
        $equipment = Equipment::findOrFail($equip_id);
        $request['user_id']= Auth::id();
        $request['equipment_id']= $equip_id;
        $request['date']= $request->date != null ? $request->date : Carbon::now();
        $request['note'] = $request->note;
        $atribute = $request->all();
        $inventory = Inventory::create($atribute);
        $number = HistoryInventories::where('equipment_id',$equipment->id)->orderBy('times', 'desc')->first();
        $history_inventory = HistoryInventories::create($atribute);
        if(isset($number)){
            $history_inventory['times'] = ($number->times)+1;
            $history_inventory->save();
        }
        if($inventory){
            activity()->causedBy(Auth::user())->performedOn($equipment)->withProperties(['attributes'=>$equipment])->log('inventory');
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        }else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Bad Request'
            ],400);
        }
    }

    public function listInventoryByEquipmentID( $equip_id) {
        try{
            $equipments = DB::table('inventories')
                ->leftjoin('equipments', 'inventories.equipment_id', '=', 'equipments.id');
                $equipments = $equipments->where('equipments.id',$equip_id)
                ->select('equipment_id','inventories.*','equipments.title', 'equipments.model', 'equipments.serial')
                ->orderBy('date','desc')->get();
            return response()->json([
                'status_code' => 200,
                'data' => $equipments,
                'dataLength' => $equipments->count(),
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status_code' => 500,
                'message' => 'Server not responding',
                'error' => $e,
            ],500);
        }
    }
}
