<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transfer;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Device;
use App\Models\Cates;
use PDF;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Notifications\TransferNotifications;
use App\Notifications\PublicTransferNotifications;
use App\Notifications\CancelTransferNotifications;

class TransferController extends Controller
{
    public function index(Request  $request)
    {
        $user = Auth::user();
        if ($user->can('transfer.read')) {
            $keyword = isset($request->key) ? $request->key : '';
            $status_key = isset($request->status_key) ? $request->status_key : '';
            $transfers = Transfer::with('transfer_equipment', 'transfer_department', 'transfer_user');
            if ($status_key != '') {
                $transfers = $transfers->where('transfers.status', $status_key);
            }
            if ($keyword != '') {
                $transfers = $transfers->whereHas('transfer_equipment', function ($query) use ($keyword) {
                    $query->select('id', 'title')->where('equipments.title', 'like', '%' . $keyword . '%');
                })->orWhereHas('transfer_department', function ($q) use ($keyword) {
                    $q->select('id', 'title')->where('departments.title', 'like', '%' . $keyword . '%');
                });
            }
            $transfers = $transfers->orderBy('created_at', 'desc')->paginate(15);
            return view('backends.transfers.list', compact('transfers', 'keyword', 'status_key'));
        } else {
            abort(403);
        }
    }
    public function pdf()
    {
        $transfers = Transfer::all();
        $pdf = PDF::loadView('backends.transfers.transfer_pdf', compact('transfers'));
        return $pdf->download('Phiếu điều chuyển thiết bị.pdf');
    }
    public function showPdf($id)
    {
        $transfers = Transfer::findOrFail($id);
        $pdf = PDF::loadView('backends.transfers.transfer_show_pdf', compact('transfers'));
        return $pdf->download('' . $transfers->transfer_equipment->title . '.pdf');
    }

    public function wordExport($id)
    {
        $transfers = Transfer::findOrFail($id);

        $transfersWord = new TemplateProcessor('word-template/text.docx');
        $transfersWord->setImageValue('image', imageAutoWord($transfers->image));
        $transfersWord->setValue('equipment_id',  isset($transfers->transfer_equipment->title) ? $transfers->transfer_equipment->title : '');
        $transfersWord->setValue('department_id',  isset($transfers->transfer_department->title) ? $transfers->transfer_department->title : '');
        $transfersWord->setValue('department_user_id',  isset($transfers->transfer_department) && isset($transfers->transfer_department->department_users) ? $transfers->transfer_department->department_users->name : '');
        $transfersWord->setValue('department_users_id',  isset($transfers->transfer_department) && isset($transfers->transfer_department->users) ? $transfers->transfer_department->user->name : '');
        //     $transfersWord->setValue('department_users_id',  isset($transfers->transfer_department) && isset($transfers->transfer_department->users) ? $transfers->transfer_department->users->name :'' );
        $transfersWord->setValue('unit_id',  isset($transfers->transfer_equipment) && isset($transfers->transfer_equipment->equipment_unit) ? $transfers->transfer_equipment->equipment_unit->title : '');
        $transfersWord->setValue('model',  isset($transfers->transfer_equipment->model) ? $transfers->transfer_equipment->model : '');
        $transfersWord->setValue('manufacturer',  isset($transfers->transfer_equipment->manufacturer) ? $transfers->transfer_equipment->manufacturer : '');
        $transfersWord->setValue('year_manufacture',  isset($transfers->transfer_equipment->year_manufacture) ? $transfers->transfer_equipment->year_manufacture : '');
        $transfersWord->setValue('serial',  isset($transfers->transfer_equipment->serial) ? $transfers->transfer_equipment->serial : '');
        $transfersWord->setValue('unit_id',  isset($transfers->transfer_equipment) && isset($transfers->transfer_equipment->equipment_unit) ? $transfers->transfer_equipment->equipment_unit->title : '');
        $transfersWord->setValue('amount',  $transfers->amount);
        $transfersWord->setValue('note', $transfers->note);
        $transfersWord->setValue('content', $transfers->content);
        $transfersWord->setValue('user_id', isset($transfers->transfer_user->name) ? $transfers->transfer_user->name : '');
        $transfersWord->setValue('user_department_id',  isset($transfers->transfer_user) && isset($transfers->transfer_user->user_department) ? $transfers->transfer_user->user_department->title : '');
        $transfersWord->setValue('provider_id',  isset($transfers->transfer_equipment) && isset($transfers->transfer_equipment->equipment_provider) ? $transfers->transfer_equipment->equipment_provider->title : '');
        $fileName = $transfers->transfer_equipment->title;
        $transfersWord->saveAs($fileName . '.docx');
        return response()->download($fileName . '.docx')->deleteFileAfterSend(true);
    }
    public function create()
    {
        $user = Auth::user();
        if ($user->can('create', Transfer::class)) {
            $equipments = Equipment::select('id', 'title', 'serial', 'code', 'model')
                ->where('department_id', $user->department_id)
                ->whereIn('status', ['not_handed', 'active'])->get();
            /*->whereHas('equipment_transfer', function ($query) {
                $query->where('transfers.status', '!=', 'pendding');
            })->get();*/
            $departments = Department::select('id', 'title')->where('id', '!=', $user->department_id)->get();
            $cur_day = Carbon::now()->format('Y-m-d');
            return view('backends.transfers.create', compact('equipments', 'departments', 'cur_day'));
        } else {
            abort(403);
        }
    }
    public function store(Request  $request)
    {
        $equipment = Equipment::findOrFail($request->equipment_id);
        $rules = [
            'equipment_id' => 'required',
            'department_id' => 'required',
            'amount' => 'required|numeric|max:' . intval($equipment->amount) . '|min:0',
            'time_move' => 'required',
        ];
        $messages = [
            'equipment_id.required' => 'Vui lòng chọn thiết bị !',
            'department_id.required' => 'Vui lòng chọn khoa phòng ban !',
            'amount.required' => 'Vui lòng nhập số lượng !',
            'amount.min' => 'Vui lòng nhập số lượng không ít hơn 0 !',
            'amount.max' => 'Thiết bị ' . $equipment->title . ' số lượng chỉ còn ' . intval($equipment->amount) . ' vui lòng nhập ít hơn !',
            'time_move.required' => 'Vui lòng nhập ngày điều chuyển !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->back()->withErrors($validator)->withInput();
        else :
            $atribute = $request->all();
            $atribute['status'] = "pendding";
            $transfers = Transfer::create($atribute);
            //notify
            $array_user = getUserToNotify($equipment->id);
            if ($array_user != null) {
                foreach ($array_user as $key => $value) {
                    $user = User::findOrFail($value);
                    $user->notify(new TransferNotifications($transfers));
                }
            }
            if ($transfers) {
                return redirect()->route('transfer.index')->with('success', 'Đã gửi phiếu điều chuyển thiết bị ' . $transfers->transfer_equipment->title . ' đến ' . $transfers->transfer_department->title . '');
            } else {
                return redirect()->back()->with('error', 'Thêm không thành công');
            };
        endif;
    }
    public function edit($id, $supplies_id)
    {
        $user = Auth::user();
        $transfers = Transfer::findOrFail($id);
        if ($user->can('transfer.approved')) {
            $equipments_supplies = Equipment::findOrFail($supplies_id);
            $equipments = Equipment::select('id', 'title', 'code', 'serial', 'model')->where('department_id', $user->department_id)->where('status', '!=', 'was_broken')->get();
            $departments = Department::select('id', 'title')->where('id', '!=', $user->department_id)->get();
            $cur_day = Carbon::now()->format('Y-m-d');
            return view('backends.transfers.edit', compact('equipments', 'cur_day', 'departments', 'transfers', 'equipments_supplies'));
        } else {
            abort(403);
        }
    }
    public function update(Request  $request, $id)
    {
        $transfers = Transfer::findOrFail($id);
        $atribute = $request->all();
        $transfers->update($atribute);
        $equipments = Equipment::where('id', $transfers->equipment_id)->first();
        $cates = Cates::select("id", "code")->where('id', $transfers->transfer_equipment->cate_id)->first();
        $devices = Device::select("id", "code")->where('id', $transfers->transfer_equipment->devices_id)->first();
        $newYear = Carbon::now()->format('dmY');
        $padded_cates = Str::padLeft(isset($cates->code) ? $cates->code : '', 1, 'X');
        $padded_devices = Str::padLeft(isset($devices->code) ? $devices->code : '', 6, 'X');
        if ($request->status == "public") {
            //notify
            // $user= User::where('id',$transfers->user_id)->first();
            // $roles = [$user->roles->first()->name];
            // $array_user = User::role($roles)->pluck('id')->toArray();

            $array_user = getUserToNotify($equipments->id);
            if ($array_user != null) {
                foreach ($array_user as $key => $value) {
                    $user = User::findOrFail($value);
                    $user->notify(new PublicTransferNotifications($transfers));
                }
            }

            if (intval($equipments->amount) > intval($transfers->amount)) {
                $equipments->amount = intval($equipments->amount) - intval($transfers->amount);
                $equipments->save();
                $equipments_v2 = Equipment::where('parent_id', $equipments->id)->where('department_id', $transfers->department_id)->first();
                if ($equipments_v2) {
                    $equipments_v2->amount = intval($equipments_v2->amount) + intval($transfers->amount);
                    $equipments_v2->save();
                } else {
                    $equipments_v1 = $equipments->replicate();
                    $equipments_v1->amount = $transfers->amount;
                    $equipments_v1->department_id = $transfers->department_id;
                    $equipments_v1->parent_id = $equipments->id;
                    $equipments_v1->save();
                    $padded_equipments_id = Str::padLeft($equipments_v1->id, 6, 'X');
                    $equipments_v1->update(['code' => $padded_cates . '-' . $padded_devices . '-' . $newYear . '-' . $padded_equipments_id]);
                }
                return redirect()->route('transfer.index')->with('success', 'Cập nhật thành công');
            } elseif (intval($equipments->amount) == intval($transfers->amount)) {
                $equipments->department_id = $transfers->department_id;
                $equipments->save();
                return redirect()->route('transfer.index')->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back()->with('error', 'Cập nhật không thành công');
            }
        } elseif ($request->status == "cancel") {
            //notify
            // $user= User::where('id',$transfers->user_id)->first();
            // $roles = [$user->roles->first()->name];
            // $array_user = User::role($roles)->pluck('id')->toArray();
            $array_user = getUserToNotify($equipments->id);
            if ($array_user != null) {
                foreach ($array_user as $key => $value) {
                    $user = User::findOrFail($value);
                    $user->notify(new CancelTransferNotifications($transfers));
                }
            }
            if ($transfers->wasChanged())
                return redirect()->back()->with('success', 'Hủy thiết bị thành công');
            else
                return redirect()->back();
        } else {
            return redirect()->back()->with('error', 'Cập nhật không thành công');
        }
    }
    public function destroy($id)
    {
        $user = Auth::user();
        $transfers = Transfer::findOrFail($id);
        if ($user->can('delete', $transfers)) {
            $transfers->delete();
            \DB::table('notifications')
                ->where('type', 'App\Notifications\TransferNotifications')
                ->orWhere('type', 'App\Notifications\PublicTransferNotifications')
                ->orWhere('type', 'App\Notifications\CancelTransferNotifications')
                ->where('data->id', intval($id))
                ->delete();
            return redirect()->route('transfer.index')->with('success', 'Xóa thành công');
        } else {
            abort(403);
        }
    }
    public function getQuantity(Request  $request)
    {
        $equipment = Equipment::with('equipment_department')->select('id', 'title', 'amount', 'department_id')->where('id', $request->id)->first();
        $department = Department::select('id', 'title')->where('id', $request->id)->first();
        return response()->json([
            'check' => 'true',
            'department' => $department,
            'equipment' => $equipment,
        ]);
    }
}
