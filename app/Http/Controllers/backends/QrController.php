<?php

namespace App\Http\Controllers\backends;

use App\Models\Department;
use App\Models\Equipment;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class QrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if($user->can('qr.show_all')){
            $departments = Department::has('department_equipment')->with('department_equipment', 'inventories')->paginate(15);
        }else {
            $depart_id = $user->user_department->id;
            $departments = Department::findOrFail($depart_id);
        }
        $data = [
            'departments'        => $departments,
        ];
        return view('backends.qr.list-department', $data);
    }

    public function listEquipment(Request $request, $depart_id)
    {
        $user = Auth::user();
        $department = Department::findOrFail($depart_id);
        $data = [
            'department'         => $department,
            'equipments'      => $department->department_equipment->load('inventories', 'equipment_department')->sortBy('created_at')->simplePaginate(10),
        ];
        return view('backends.qr.list-equipment', $data);
    }

    public function showPdf(Request $request, $depart_id)
    {
        $department = Department::findOrFail($depart_id);
        $equipments = $department->department_equipment->sortBy('created_at');
        $data = [
            'department'         => $department,
            'equipments'         => $equipments,
        ];
         return view('backends.qr.pdf', compact('data'));
        $pdf = PDF::loadView('backends.qr.pdf', compact('data'));
        return $pdf->download('Danh sách QR code ' . $department->title . '.pdf');
    }
    public function PrintPdf(Request $request, $equip_id)
    {
        $equipment = Equipment::findOrFail($equip_id);

        return view('backends.qr.pdf-equipment', compact('equipment'));
//        $pdf = PDF::loadView('backends.qr.pdf-equipment', compact('equipment'));
//        return $pdf->download('QR code ' . $equipment->title . '.pdf');
    }

    public function store(Request $request)
    {
        //
    }

}
