<?php

namespace App\Http\Controllers\backends;

use App\Exports\ExportInspectionList;
use App\Http\Controllers\Controller;
use App\Models\Accre;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\User;
use App\Notifications\AccreNotifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AccreController extends Controller
{

    public function index(Request $request)
    {
        // dd($request);
        $user = Auth::user();
        $equipments = Equipment::with('equipment_department');
        $departments_key = isset($request->departments_key) ? $request->departments_key : '';
        $keyword = isset($request->keyword) ? $request->keyword : '';
        $inspections_key = isset($request->inspection) ? $request->inspection : '';
        $time_inspection = isset($request->time_inspection) ? $request->time_inspection : '';
        $inspec_time = isset($_GET['inspec_time']) ? ($_GET['inspec_time']) : '';

        // Xử lí ngày tháng lọc Thời gian KĐ cuối
        $start = date_format(date_create($time_inspection), 'Y-m-d'); // ngày tháng
        //dd($time_inspection);
        // $start1 = strtotime(date_format(date_create($myKDs), 'Y-m-d')); // trả về string milis
        // $starts = strtotime($myKDs); // trả về string milis
        $end = date("Y-m-d", strtotime("+" . "1" . "months", strtotime($start)));  // ngày tháng


        if ($inspec_time == '') {
            if ($user->can('eqaccre.read')) {
                if ($inspections_key != '') {
                    $equipments = $equipments->where('regular_inspection', $inspections_key);
                }

                $department_name = Department::select('id', 'title')->get();
                if ($departments_key != '') {
                    $equipments = $equipments->where('department_id', $departments_key);
                }
                if ($time_inspection != '') {
                    $equipments = $equipments->whereBetween('last_inspection', [$start, $end]);
                }
            } else {
                $equipments = $equipments->where('user_id', $user->id);
                if ($inspections_key != '') {
                    $equipments = $equipments->where('regular_inspection', $inspections_key);
                }

                $department_name = Department::where('id', $user->department_id)->select('id', 'title')->get();
                $equipments = $equipments->where('department_id', $user->department_id);
                if ($departments_key != '') {
                    $equipments = $equipments->where('department_id', $departments_key);
                }
                if ($time_inspection != '') {
                    $equipments = $equipments->whereBetween('last_inspection', [$start, $end]);
                }
            }


            if ($keyword != '') {
                $equipments = $equipments->where(function ($query) use ($keyword) {
                    $query->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('code', 'like', '%' . $keyword . '%')
                        ->orWhere('model', 'like', '%' . $keyword . '%')
                        ->orWhere('serial', 'like', '%' . $keyword . '%');
                });
            };

            $equipments = $equipments->whereNotIn('status', ['inactive', 'liquidated'])->orderBy('last_inspection', 'desc')->paginate(15);
            $data = [
                'equipments' => $equipments,
                'keyword' => $keyword,
                'inspections_key' => $inspections_key,
                'departments_key' => $departments_key,
                'department_name' => $department_name,
                'time_inspection' => $time_inspection,
                'inspec_time' => $inspec_time
            ];
        } else {
            if ($user->can('eqaccre.read')) {
                if ($inspections_key != '') {
                    $equipments = $equipments->where('regular_inspection', $inspections_key);
                }

                $department_name = Department::select('id', 'title')->get();
                if ($departments_key != '') {
                    $equipments = $equipments->where('department_id', $departments_key);
                }
                if ($time_inspection != '') {
                    $equipments = $equipments->whereBetween('next_inspection', [$start, $end]);
                }
            } else {
                $equipments = $equipments->where('user_id', $user->id);
                if ($inspections_key != '') {
                    $equipments = $equipments->where('regular_inspection', $inspections_key);
                }

                $department_name = Department::where('id', $user->department_id)->select('id', 'title')->get();
                $equipments = $equipments->where('department_id', $user->department_id);
                if ($departments_key != '') {
                    $equipments = $equipments->where('department_id', $departments_key);
                }
                if ($time_inspection != '') {
                    $equipments = $equipments->whereBetween('next_inspection', [$start, $end]);
                }
            }


            if ($keyword != '') {
                $equipments = $equipments->where(function ($query) use ($keyword) {
                    $query->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('code', 'like', '%' . $keyword . '%')
                        ->orWhere('model', 'like', '%' . $keyword . '%')
                        ->orWhere('serial', 'like', '%' . $keyword . '%');
                });
            };

            $equipments = $equipments->whereNotIn('status', ['inactive', 'liquidated'])->orderBy('next_inspection', 'desc')->paginate(15);
            $data = [
                'equipments' => $equipments,
                'keyword' => $keyword,
                'inspections_key' => $inspections_key,
                'departments_key' => $departments_key,
                'department_name' => $department_name,
                'time_inspection' => $time_inspection,
                'inspec_time' => $inspec_time,
            ];
        }


        return view('backends.accres.list', $data);
    }

    public function exportEquipInspec(Request $request)
    {
        $inspec_time = isset($request->inspec_time) ? $request->inspec_time : '';
        $keyword = isset($request->keyword) ? $request->keyword : '';
        $departments_key = isset($request->departments_key) ? $request->departments_key : '';
        $inspections_key = isset($request->inspections_key) ? $request->inspections_key : '';
        $time_inspection = isset($request->time_inspection) ? $request->time_inspection : '';
        // dd($request);
        $department_name = "";
        if ($departments_key != '') {
            $department_name = Department::findOrFail($departments_key)->title;
        }


        if ($inspec_time != '') {
            return Excel::download(new ExportInspectionList(
                $inspec_time,
                $departments_key,
                $keyword,
                $inspections_key,
                $time_inspection
            ), 'Danh sách thiết bị ' . $department_name . 'kiểm định tiếp theo : ' . $time_inspection . '.xlsx');
        } else {
            return Excel::download(
                new ExportInspectionList(
                    $inspec_time,
                    $departments_key,
                    $keyword,
                    $inspections_key,
                    $time_inspection
                ),
                'Danh sách thiết bị ' . $department_name . ' đã kiểm định gần nhất : ' . $time_inspection . '.xlsx'
            );
        }
    }

    public function store(Request $request, $id)
    {
        $rules = [
            'time' => 'required',
            'provider' => 'required',
            'content' => 'required',
        ];
        $messages = [
            'time.required' => 'Vui lòng chọn thời gian kiểm định',
            'provider.required' => 'Vui lòng nhập đơn vị thực hiện',
            'content.required' => 'Vui lòng nhập nội dung kiểm định',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->back()->withErrors($validator)->withInput();
        else :
            $equipments = Equipment::findOrFail($id);
            // Update table Accre
            $atributeAccre = $request->all();
            $atributeAccre['equipment_id'] = $equipments->id;
            Accre::create($atributeAccre);


            // update column last_inspection & next_inspection
            $equipments['last_inspection'] = $request->time;
            if ($equipments['regular_inspection']) {

                $m = $equipments['regular_inspection'];

                $equipments['next_inspection'] = date("Y-m-d", strtotime("+" . $m . "months", strtotime($equipments['last_inspection'])));
            };

            $equipments->save();
            //notifications
            $array_user = getUserToNotify($equipments->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
            if ($array_user != null) {
                foreach ($array_user as $key => $value) {
                    $user = User::findOrFail($value);
                    $user->notify(new AccreNotifications($equipments));
                }
            }

            //send email
            $content = '';
            $content .= '<div class="content">
                                <h4>' . __('Thông tin thiết bị được tạo lịch kiểm định') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipments->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipments->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipments->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipments->serial . '</td></tr>
                                        <tr><td>' . __('Ngày kiểm định lần cuối: ') . '</td><td>' . $equipments->last_inspection . '</td></tr>
                                        <tr><td>' . __('Chu kỳ kiểm định: ') . '</td><td>' . $equipments->regular_inspection . '</td></tr>
                                        <tr><td>' . __('Kiểm định lần tới: ') . '</td><td>' . $equipments->next_inspection . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

            $array_emails = getUserToMail($equipments->id);
            $data = array('email' => $array_emails, 'equipments_department' => $equipments->equipment_department, 'from' => 'phongvt.ttb.bvkienan@gmail.com', 'content' => $content, 'title' => $equipments->title);
            Mail::send('mails.fail', compact('data'), function ($message) use ($data) {
                $message->to($data['email'])
                    ->from($data['from'], '[Thông báo kiểm định]')
                    ->subject('Thiết bị ' . ' [' . $data['title'] . '] đã được tạo lịch kiểm định');
            });

            return redirect()->route('accre.index')->with('success', 'Thêm thành công');
        endif;
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'time' => 'required',
            'provider' => 'required',
            // 'content'=>'required',
        ];
        $messages = [
            'time.required' => 'Vui lòng chọn thời gian kiểm định',
            'provider.required' => 'Vui lòng nhập đơn vị thực hiện',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->back()->withErrors($validator)->withInput();
        else :
            $accre = Accre::findOrFail($id);
            $atribute = $request->all();

            $equipments = Equipment::findOrFail($accre->equipment_id);
            $equipments['last_inspection'] = $request->time;
            if ($equipments['regular_inspection']) {

                $m = $equipments['regular_inspection'];

                $equipments['next_inspection'] = date("Y-m-d", strtotime("+" . $m . "months", strtotime($equipments['last_inspection'])));
            };
            $equipments->save();
            $accre->update($atribute);
            if ($accre) {
                if ($accre->wasChanged()) {
                    //notifications
                    $array_user = getUserToNotify($equipments->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
                    if ($array_user != null) {
                        foreach ($array_user as $key => $value) {
                            $user = User::findOrFail($value);
                            $user->notify(new AccreNotifications($equipments));
                        }
                    }

                    $content = '';
                    $content .= '<div class="content">
                                <h4>' . __('Thông tin thiết bị được cập nhật lịch kiểm định') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipments->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipments->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipments->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipments->serial . '</td></tr>
                                        <tr><td>' . __('Ngày kiểm định lần cuối: ') . '</td><td>' . $equipments->last_inspection . '</td></tr>
                                        <tr><td>' . __('Chu kỳ kiểm định: ') . '</td><td>' . $equipments->regular_inspection . '</td></tr>
                                        <tr><td>' . __('Kiểm định lần tới: ') . '</td><td>' . $equipments->next_inspection . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

                    $array_emails = getUserToMail($equipments->id);
                    $data = array('email' => $array_emails, 'equipments_department' => $equipments->equipment_department, 'from' => 'phongvt.ttb.bvkienan@gmail.com', 'content' => $content, 'title' => $equipments->title);
                    Mail::send('mails.fail', compact('data'), function ($message) use ($data) {
                        $message->to($data['email'])
                            ->from($data['from'], '[Thông báo cập nhật kiểm định]')
                            ->subject('Thiết bị ' . ' [' . $data['title'] . '] đã được cập nhật lịch kiểm định');
                    });
                    return redirect()->back()->with('success', 'Cập nhật thành công');
                } else {
                    return redirect()->back();
                }
            } else {
                return redirect()->back()->with('error', 'Cập nhật không thành công');
            }
        endif;
    }


    public function edit($id)
    {
        $equipments = Equipment::findOrFail($id);
        $accre = $equipments->accres;
        return view('backends.accres.edit', compact('equipments'));
    }

    public function destroy($id)
    {
        $equipments = Accre::findOrFail($id);
        $equipments->delete();
        return redirect()->back()->with('success', 'Xóa thành công');
    }

    public function exportEquipInspecNextMonth()
    { //export all equipment that needs to be inspected in the next month
        //take all the equipments that have next_inspection time in the next month
        $start = new Carbon('first day of next month');
        $time_inspection = $start->format('Y-m');

        //export
        return Excel::download(
            new ExportInspectionList(
                'next', //export according to next_inspection
                '',
                '',
                '',
                $time_inspection
            ),
            'Danh sách thiết bị cần kiểm định trong tháng ' . $time_inspection . '.xlsx'
        );
    }
}
