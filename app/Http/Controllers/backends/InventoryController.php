<?php

namespace App\Http\Controllers\backends;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cates;
use App\Models\Device;
use App\Models\Equipment;
use App\Models\Inventory;
use App\Models\Department;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use App\Models\HistoryInventories;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportEquipmentInventory;
use Illuminate\Support\Facades\Validator;
use App\Notifications\InventoryNotifications;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::has('department_equipment')->with('department_equipment', 'inventories')->paginate(15);
        $data = [
            'departments'        => $departments,
        ];
        return view('backends.inventories.list-department', $data);
    }

    public function listEquipment(Request $request, $depart_id)
    {
        $user = Auth::user();
        $department = Department::findOrFail($depart_id);
        $data = [
            'department'         => $department,
            'equipments'      => $department->department_equipment->load('inventories', 'equipment_department')->sortBy('created_at')->simplePaginate(10),
        ];
        return view('backends.inventories.list-equipment', $data);
    }
    public function historyInventory(Request $request, $depart_id)
    {
        $user = Auth::user();
        $department = Department::findOrFail($depart_id);
        $data = [
            'department'         => $department,
            'inventories'      => $department->history_inventories->sortBy('created_at')->simplePaginate(10),
        ];
        return view('backends.inventories.history-inventory', $data);
    }

    public function create($equip_id)
    {
        $user = Auth::user();
        $equipment = Equipment::findOrFail($equip_id);
        $data = [
            'equipment'         => $equipment,
        ];
        return view('backends.inventories.create-inventory', $data);
    }
    public function store(Request $request, $equip_id)
    {
        $equipment = Equipment::findOrFail($equip_id);
        $request['user_id'] = Auth::id();
        $request['equipment_id'] = $equip_id;
        $request['date'] = $request->date != null ? $request->date : Carbon::now();
        $atribute = $request->all();
        $inventory = Inventory::create($atribute);
        $number = HistoryInventories::where('equipment_id', $equipment->id)->orderBy('times', 'desc')->first();
        $history_inventory = HistoryInventories::create($atribute);
        if (isset($number)) {
            $history_inventory['times'] = ($number->times) + 1;
            $history_inventory->save();
        }
        if ($inventory) {
            activity()->causedBy(Auth::user())->performedOn($equipment)->withProperties(['attributes' => $equipment])->log('inventory');
            //notification on success
            $array_user = getUserToNotify($equipment->id);
            if ($array_user != null) {
                foreach ($array_user as $key => $value) {
                    $user = User::findOrFail($value);
                    $user->notify(new InventoryNotifications($equipment));
                }
            }
            return redirect()->route('inventory.listEquipment', ['depart_id' => $equipment->equipment_department->id])->with('success', 'Kiểm kê thành công');
        } else {
            return redirect()->route('inventory.listEquipment', ['depart_id' => $equipment->equipment_department->id])->with('error', 'Kiểm kê thất bại');
        }
    }
    public function listInventory(Request $request, $equip_id)
    {
        $user = Auth::user();
        $equipment = Equipment::findOrFail($equip_id);
        $data = [
            'equipment'         => $equipment,
            'inventories'      => $equipment->history_inventories->sortByDesc('created_at')->simplePaginate(10),
        ];
        return view('backends.inventories.list-inventory', $data);
    }
    public function destroy($equip_id, $id)
    {
        $user = Auth::user();
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();
        return redirect()->route('inventory.listInventory', ['equip_id' => $equip_id])->with('success', 'Xóa thành công');
    }
    public function resetInventory(Request $request, $depart_id)
    {
        $user = Auth::user();
        if ($user->can('inventory.eq')) {
            $department = Department::findOrFail($depart_id);
            $department->browser = null;
            $department->browser_day = null;
            $department->save();
            $inventories = $department->inventories;
            foreach ($inventories as $value) {
                $value->delete();
            }
            /*$attach = $department->attachments->first();
            if($attach){
                $attach->delete();
            }*/
            return redirect()->route('inventory.listEquipment', ['depart_id' => $department->id])->with('success', 'Kiểm kê lại thành công');
        } else {
            abort(403);
        }
    }
    public function completedInventory(Request $request, $depart_id)
    {
        $user = Auth::user();
        $department = Department::findOrFail($depart_id);
        $data = [
            'department'         => $department,
            'equipments'      => $department->department_equipment->load('inventories', 'equipment_department') //eager load
            ->sortBy('created_at')->simplePaginate(10),
        ];
        return view('backends.inventories.list-complete', $data);
    }
    public function browserInventory(Request $request, $depart_id)
    {
        $user = Auth::user();
        $department = Department::findOrFail($depart_id);
        $department->browser = 'browser';
        $department->browser_day = Carbon::now();
        $department->save();
        // Attachment
        if ($request->attachment && $request->attachment != '' && is_array(explode(',', $request->attachment)))
            $department->attachments()->attach(array_filter(explode(',', $request->attachment)), ['type' => 'inventory']);

        return redirect()->route('inventory.completedInventory', ['depart_id' => $department->id])->with('success', 'Duyệt thành công');
    }
    public function exportEquipment(Request $request, $depart_id)
    {
        $department = Department::findOrFail($depart_id);
        return Excel::download(new ExportEquipmentInventory($depart_id), 'Danh sách hoàn thành kiểm kê ' . $department->title . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
}
