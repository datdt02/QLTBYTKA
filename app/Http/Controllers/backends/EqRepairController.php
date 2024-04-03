<?php

namespace App\Http\Controllers\backends;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\User;
use App\Models\Device;
use App\Models\Supplie;
use App\Models\Provider;
use App\Models\Transfer;
use App\Models\Equipment;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ScheduleRepair;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Validator;
use App\Notifications\RepairNotifications;
use App\Exports\EquipmentRepairHistoryExport;
use App\Notifications\PublicRepairNotifications;
use Maatwebsite\Excel\Excel as BaseExcel;

class EqRepairController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->can('eqrepair.read')) {
            $eqrepairs = Equipment::with('schedule_repairs', 'equipment_department');
            $keyword = isset($request->key) ? $request->key : '';
            $department_id = isset($request->department_id) ? $request->department_id : '';
            $device_id = isset($request->device_id) ? $request->device_id : '';
            $status_id = isset($request->status_id) ? $request->status_id : '';
            $critical_level = isset($request->critical_level) ? $request->critical_level : '';
            $departments = Department::select('id', 'title')->get();
            $devices = Device::select('id', 'title')->get();
            if ($keyword != '') {
                $eqrepairs = $eqrepairs->where(function ($query) use ($keyword) {
                    $query->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('code', 'like', '%' . $keyword . '%')
                        ->orWhere('model', 'like', '%' . $keyword . '%')
                        ->orWhere('serial', 'like', '%' . $keyword . '%')
                        ->orWhere('manufacturer', 'like', '%' . $keyword . '%');
                });
            }
            if ($department_id != '') {
                $eqrepairs = $eqrepairs->where('department_id', $department_id);
            }
            if ($device_id != '') {
                $eqrepairs = $eqrepairs->whereHas('equipment_device', function ($query) use ($device_id) {
                    $query->where('device_id', $device_id);
                });
            }
            if ($status_id != '') {
                $eqrepairs = $eqrepairs->where('status', $status_id);
            }
            if ($critical_level != '') {
                $eqrepairs = $eqrepairs->where('critical_level', $critical_level);
            }
            $eqrepairs = $eqrepairs->whereIn('status', ['corrected', 'was_broken'])->orderBy('status', 'asc')->paginate(10);
            $data = [
                'eqrepairs' => $eqrepairs,
                'keyword' => $keyword,
                'department_id' => $department_id,
                'device_id' => $device_id,
                'status_id' => $status_id,
                'departments' => $departments,
                'devices' => $devices,
                'critical_level' => $critical_level,
            ];
            return view('backends.eqrepairs.list', $data);
        } else {
            abort(403);
        }
    }
    public function create($equip_id)
    {
        $user = Auth::user();
        if ($user->can('create', ScheduleRepair::class)) {
            $equipments = Equipment::findOrFail($equip_id);
            $repairs = Provider::select('id', 'title', 'type')->repair()->get();
            $data = [
                'equipments' => $equipments,
                'repairs' => $repairs,
            ];
            return view('backends.eqrepairs.create', $data);
        } else {
            abort(403);
        }
    }
    public function store(Request $request, $equip_id)
    {
        $rules = [
            // 'provider_id'=>'required',
        ];
        $messages = [
            // 'provider_id.required'=>'Vui lòng chọn đơn vị sửa chữa !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->route('eqrepair.create', ['equip_id' => $equip_id])->withErrors($validator)->withInput();
        else :
            $equipment = Equipment::findOrFail($equip_id);
            $request['user_id'] = Auth::id();
            $request['planning_date'] = Carbon::now()->toDateString();
            $schedule_repairs = $equipment->schedule_repairs()->create($request->all());
            $padded_repair_id = Str::padLeft($schedule_repairs->id, 6, 'X');
            $newYear = Carbon::now()->format('Ymd-His');
            $schedule_repairs['code'] = $newYear . '-' . $padded_repair_id;
            $schedule_repairs->save();
            $equipment['status'] = 'corrected';
            $equipment['critical_level'] = $request->critical_level;
            $equipment->update($request->only('reason', 'status', 'critical_level'));
            // Attachment
            if ($request->attachment && $request->attachment != '' && is_array(explode(',', $request->attachment)))
                $schedule_repairs->attachments()->attach(array_filter(explode(',', $request->attachment)), ['type' => 'repair']);

            if ($request->attachment && $request->attachment != '' && is_array(explode(',', $request->attachment)))
                $equipment->repairs()->attach(array_filter(explode(',', $request->attachment)), ['type' => 'repair']);
            //notify

            $array_user = getUserToNotify($equipment->id);
            if ($array_user != null) {
                foreach ($array_user as $key => $value) {
                    $users = User::findOrFail($value);
                    $users->notify(new RepairNotifications($schedule_repairs));
                }
            }


            //email

            $titleProvider = $schedule_repairs->provider ? $schedule_repairs->provider->title : "";
            $user = Auth::user();
            $content = '';
            $content .= '<div class="content">
                            <h4>' . __('Thông tin lịch sửa chữa thiết bị') . '</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                    <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                    <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                    <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                    <tr><td>' . __('Mức độ quan trọng: ') . '</td><td>' . $equipment->critical_level . '</td></tr>
                                    <tr><td>' . __('Thời gian bắt đầu sửa chữa: ') . '</td><td>' . $schedule_repairs->repair_date . '</td></tr>
                                    <tr><td>' . __('Đơn vị sửa chữa: ') . '</td><td>' . $titleProvider . '</td></tr>
                                    <tr><td>' . __('Thông tin liên hệ: ') . '</td><td>' . $schedule_repairs->user->displayname . '</td></tr>
                                </tbody>
                            </table>
                        </div>';
            $array_emails = getUserToMail($equipment->id);
            $data = array('email' => $array_emails, 'equipment' => $equipment, 'from' => $user->email, 'content' => $content, 'title' => $equipment->title);
            Mail::send('mails.repair', compact('data'), function ($message) use ($data) {
                $message->to($data['email'])
                    ->from($data['from'], '[Phòng VT TBYT]')
                    ->subject('[Sửa chữa] Phòng VTYT lên lịch sửa chữa thiết bị [' . $data['title'] . ']' . ' [' . $data['equipment']->code . ']');
            });
            if ($schedule_repairs) {
                activity()->causedBy(Auth::user())->performedOn($equipment)->withProperties(['attributes' => $equipment])->log($equipment->status);
                $request->session()->flash('success', 'Thêm thành công!');
            } else {
                $request->session()->flash('error', 'Thêm không thành công!');
            }
            return redirect()->route('eqrepair.index');
        endif;
    }
    public function listRepair(Request $request, $equip_id)
    {
        $equipment = Equipment::findOrFail($equip_id);
        $data = [
            'equipment'         => $equipment,
            'repairs'           => $equipment->schedule_repairs->sortByDesc('planning_date')->simplePaginate(10),
            'schedule_repairs_count' => $equipment->schedule_repairs->count(),
        ];
        return view('backends.eqrepairs.list-histories', $data);
    }
    public function listRepairExport($equip_id)
    {
        $equipment = Equipment::find($equip_id);
        return Excel::download(
            new EquipmentRepairHistoryExport($equip_id),
            'Danh sách lịch sửa chữa thiết bị' . $equipment->title . '.xlsx'
        );
    }
    public function edit($equip_id, $repair_id)
    {
        $user = Auth::user();
        $repair = ScheduleRepair::findOrFail($repair_id);
        if ($user->can('update', $repair)) {
            $equipment = Equipment::findOrFail($equip_id);
            $repairs = Provider::select('id', 'title', 'type')->repair()->get();
            $users = User::select('id', 'name')->get();
            $approved = User::whereHas("roles", function ($q) {
                $q->where("name", "Nvpvt");
            })->first();
            $data = [
                'equipment'     => $equipment,
                'repair'   => $repair,
                'repairs'   => $repairs,
                'users'   => $users,
                'approved'   => $approved,
            ];
            return view('backends.eqrepairs.edit', $data);
        } else {
            abort(403);
        }
    }
    public function update(Request $request, $equip_id, $repair_id)
    {
        $equipment = Equipment::findOrFail($equip_id);
        $repair = ScheduleRepair::findOrFail($repair_id);
        $approved = User::whereHas("roles", function ($q) {
            $q->where("name", "Nvpvt");
        })->first();
        $request['person_up'] = Auth::id();
        $request['update_date'] = Carbon::now()->toDateString();
        $request['approved'] = $approved->id;
        $request['expected_cost'] = $request->expected_cost;
        $request['actual_costs'] = $request->actual_costs;

        $equipment->critical_level = $request->critical_level;

        if ($repair->acceptance != 'accepted') {
            if ($request->acceptance == 'accepted') {
                $user = User::where('id', $repair->user_id)->first();
                $roles = [$user->roles->first()->name];
                $array_user = User::role($roles)->pluck('id')->toArray();

                $equipment->critical_level = '';

                activity()->causedBy(Auth::user())->performedOn($equipment)->withProperties(['attributes' => $equipment])->log('accepted');
                //notify
                $array_user = getUserToNotify($equip_id);
                if ($array_user != null) {
                    foreach ($array_user as $key => $value) {
                        $user = User::findOrFail($value);
                        $user->notify(new PublicRepairNotifications($repair));
                    }
                }
            }
        }
        //mail
        $acceptance = acceptanceRepair();
        $titleProvider = $repair->provider ? $repair->provider->title : "";
        $user = Auth::user();
        $content = '';
        $content .= '<div class="content">
                            <h4>' . __('Thông tin lịch sửa chữa thiết bị') . '</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                    <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                    <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                    <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                    <tr><td>' . __('Mức độ quan trọng: ') . '</td><td>' . $equipment->critical_level . '</td></tr>
                                    <tr><td>' . __('Thời gian bắt đầu sửa chữa: ') . '</td><td>' . $repair->repair_date . '</td></tr>
                                    <tr><td>' . __('Trạng thái sau cập nhật: ') . '</td><td>' . $acceptance[$request->acceptance] . '</td></tr>
                                    <tr><td>' . __('Đơn vị sửa chữa: ') . '</td><td>' . $titleProvider . '</td></tr>
                                    <tr><td>' . __('Thông tin liên hệ: ') . '</td><td>' . $repair->user->displayname . '</td></tr>
                                </tbody>
                            </table>
                        </div>';

        $array_emails = getUserToMail($equipment->id);
        $data = array('email' => $array_emails, 'from' => $user->email, 'content' => $content, 'equipment' => $equipment);
        Mail::send('mails.repair', compact('data'), function ($message) use ($data) {
            $message->to($data['email'])
                ->from($data['from'], '[Phòng VT TBYT]')
                ->subject('[Sửa chữa] Phòng VTYT cập nhật sửa chữa thiết bị [' . $data['equipment']->title . ']' . ' [' . $data['equipment']->code . ']');
        });



        $repair->update($request->all());
        $equipment->update($request->only('reason', 'critical_level'));
        // tài liệu nghiệm thu theo repair
        if ($request->attachment && $request->attachment != '' && is_array(explode(',', $request->attachment))) {
            $attachment = array();
            foreach (explode(',', $request->attachment) as $attach) {
                $attachment[$attach] = ['type' => 'repair'];
            }
            $repair->attachments()->sync($attachment);
        } else {
            $repair->attachments()->sync(array());
        }
        // tài liệu nghiệm thu theo equipment
        if ($request->attachment && $request->attachment != '' && is_array(explode(',', $request->attachment))) {
            $attachment = array();
            foreach (explode(',', $request->attachment) as $attach) {
                $attachment[$attach] = ['type' => 'repair'];
            }
            $equipment->repairs()->sync($attachment);
        } else {
            $equipment->repairs()->sync(array());
        }
        if ($repair) {
            $request->session()->flash('success', 'Cập nhật thành công!');
        } else $request->session()->flash('error', 'Cập nhật thất bại!');
        return redirect()->route('eqrepair.edit', ['equip_id' => $equip_id, 'repair_id' => $repair_id]);
    }
    public function destroy($equip_id, $repair_id)
    {
        $user = Auth::user();
        $repair = ScheduleRepair::findOrFail($repair_id);
        if ($user->can('delete', $repair)) {
            $repair->delete();
            \DB::table('notifications')
                ->where('type', 'App\Notifications\RepairNotifications')
                ->orWhere('type', 'App\Notifications\PublicRepairNotifications')
                ->where('data->id', intval($repair_id))
                ->delete();
            return redirect()->route('eqrepair.history', ['equip_id' => $equip_id])->with('success', 'Xóa thành công');
        } else {
            abort(403);
        }
    }
    public function stateTransition(Request $request, $equip_id)
    {
        $equipments = Equipment::findOrFail($equip_id);
        $equipments['status']  = $request->status;
        $statusAfterCorrect = ($equipments['status'] == 'active') ? "Đã sửa chữa, tình trạng sử dụng tốt, bàn giao lại về khoa phòng" : "Không thể khắc phục, chuyển vào kho thanh lý";
        $equipments->save();
        if ($equipments) {
            if ($equipments->wasChanged()) {
                activity()->causedBy(Auth::user())->performedOn($equipments)->withProperties(['attributes' => $equipments])->log($equipments->status);


                //mail

                $user = Auth::user();
                $content = '';
                $content .= '<div class="content">
                                <h3>' . $statusAfterCorrect . '</h3>
                                <h4>' . __('Thông tin cập nhật trạng thái thiết bị sau sửa chữa') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipments->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipments->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipments->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipments->serial . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';
                $array_emails = getUserToMail($equipments->id);
                $data = array('email' => $array_emails, 'from' => $user->email, 'content' => $content, 'equipment' => $equipments);
                Mail::send('mails.afterRepair', compact('data'), function ($message) use ($data) {
                    $message->to($data['email'])
                        ->from($data['from'], '[Phòng VT TBYT]')
                        ->subject('[Nghiệm thu] Phòng VTYT hoàn thành sửa chữa thiết bị [' . $data['equipment']->title . ']' . ' [' . $data['equipment']->code . ']');
                });
                return redirect()->back()->with('success', 'Đã chuyển trạng thái thiết bị ' . $equipments->title . ' ');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back()->with('error', 'Cập nhật không thành công');
        }
    }
    public function exportWord($id)
    {
        # code...
        $equipment = Equipment::findOrFail($id);
        if (env('APP_URL') == 'http://bvsonla.qltbyt.com') {
            $template_name = 'Phieu de nghi sua chua BVSL.docx';
        } elseif (env('APP_URL') == 'http://bvthaonguyen.qltbyt.com') {
            $template_name = 'Phieu de nghi sua chua BVTN.docx';
        } else {
            $template_name = 'Phieu de nghi sua chua.docx';
        }

        $transfersWord = new TemplateProcessor('word-template/' . $template_name);
        //$transfersWord->setImageValue('image',imageAutoWord($transfers->image));
        $transfersWord->setValue('equipment_title',  isset($equipment->title) ? $equipment->title : '');
        $transfersWord->setValue('equipment_model',  isset($equipment->model) ? $equipment->model : '');
        $transfersWord->setValue('equipment_department',  isset($equipment->equipment_department->title) ? $equipment->equipment_department->title : '');
        $transfersWord->setValue('equipment_serial',  isset($equipment->serial) ? $equipment->serial : '');
        $equipment_date_failure = isset($equipment->date_failure) ?
            Carbon::createFromFormat('Y-m-d H:i:s', $equipment->date_failure)->format('d/m/Y') : '';
        $transfersWord->setValue('equipment_date_failure',  $equipment_date_failure);
        $transfersWord->setValue('equipment_broken_reason',  isset($equipment->reason) ? $equipment->reason : '');
        $fileName = 'Phieu de nghi sua chua - ' . $equipment->title . ' - ' . (now()->format('d-m-Y'));
        $transfersWord->saveAs($fileName . '.docx');
        return response()->download($fileName . '.docx')->deleteFileAfterSend(true);
    }
}
