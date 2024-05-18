<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListEqpropertyRequest;
use App\Http\Requests\UpdateWasBrokenRequest;
use App\Models\Eqproperty;
use App\Models\Media;
use App\Models\User;
use App\Notifications\ReportFailureNotifications;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EqpropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $status = isset($request->status) ? $request->status : "";
            $department_id = isset($request->department_id) ? $request->department_id : "";
            $keyword = isset($request->keyword) ? $request->keyword : "";


            $equipments = DB::table('media')
                ->rightjoin('equipments', 'media.id', '=', 'equipments.image');

            if ($status != '') {
                $equipments = $equipments->where('status', $status);
            }

            if ($keyword != '') {
                $equipments = $equipments->where(function ($query) use ($keyword) {
                    $query->where('equipments.title', 'like', '%' . $keyword . '%')
                        ->orWhere('equipments.code', 'like', '%' . $keyword . '%')
                        ->orWhere('equipments.model', 'like', '%' . $keyword . '%')
                        ->orWhere('equipments.serial', 'like', '%' . $keyword . '%');
                });
            }


            $user = Auth::user();
            if ($user->can('equipment.show_all')) {
                if ($department_id != '') {
                    $equipments = $equipments->where('department_id', $department_id);
                }
            } else {
                $equipments = $equipments->where('department_id', $user->department_id);
                if ($department_id != '') {
                    $equipments = $equipments->where('department_id', $department_id);
                }
            }

            $equipments = $equipments->get();

            return response()->json([
                'status' => '200',
                'data' => $equipments,
                'dataLength' => $equipments->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Server not responding',
                'error' => $e,
            ],500);
        }
    }

    public function indexV2(ListEqpropertyRequest $request)
    {
        try {
            $status = isset($request->status) ? $request->status : "";
            $department_id = isset($request->department_id) ? $request->department_id : "";
            $keyword = isset($request->keyword) ? $request->keyword : "";

            $equipments = Eqproperty::with("eqproperty_img:id,path");
            if ($status != '') {
                $equipments = $equipments->status($request->status);
            }
            $equipments = $equipments
                ->orWhere(function (Builder $query) use ($request) {
                    $keyword = $request->keyword;
                    $query->title($keyword)
                        ->orWhere->serial($keyword)
                        ->orWhere->code($keyword)
                        ->orWhere->model($keyword)
                        ->orWhere->manufacturer($keyword)
                        ->orWhere->origin($keyword);
                });

            $user = Auth::user();
            if ($user->can('eqproperty.show_all')) {
                if ($department_id != '') {
                    $equipments = $equipments->department($request->department_id);
                }
            } else {
                $equipments = $equipments->where('department_id', $user->department_id);
                if ($department_id != '') {
                    $equipments = $equipments->department($request->department_id);
                }
            }

            $equipments = $equipments->simplePaginate($request->per_page ?? 15);

            return response()->json([
                'status' => '200',
                'data' => $equipments,
                'dataLength' => $equipments->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Server not responding',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $equipment = Eqproperty::findOrFail($id);

            if ($user->can('eqproperty.show_all') || $user->department_id == $equipment->department_id) {

                $idImg = $equipment->image;
                $pathImg = Media::find($idImg) ? Media::find($idImg)->getLink() : null;

                $equipment->urlImg = $pathImg;
                return response()->json([
                    'data' => $equipment,
                ]);
            } else {
                return response()->json([
                    'status_code' => 403,
                    'message' => 'User does not have permission'
                ],403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Not Found',
                'error' => $e,
            ],500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateWasBroken(UpdateWasBrokenRequest $request, $id)
    {
        try {

            $user = Auth::user();
            $equipments = Eqproperty::findOrFail($id);

            if ($user->can('eqproperty.show_all') || $user->department_id == $equipments->department_id) {

                $equipments['status'] = "was_broken";
                $equipments->save();

                if ($equipments->wasChanged('status')) {
                    $equipments['date_failure'] = Carbon::now()->toDateTimeString();
                    $equipments->update($request->only('date_failure', 'reason'));
                    $equipments->eqproperty_user_use()->attach($request->equipment_user_use);
                    if ($request->file && $request->file != '' && is_array(explode(',', $request->file)))
                        $equipments->was_broken()->attach(array_filter(explode(',', $request->file)), ['type' => 'was_broken']);
                    $content = '';
                    $content .= '<div class="content">
                                    <h4>' . __('Thông tin thiết bị báo hỏng') . '</h4>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipments->title . '</td></tr>
                                            <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipments->hash_code . '</td></tr>
                                            <tr><td>' . __('Model: ') . '</td><td>' . $equipments->model . '</td></tr>
                                            <tr><td>' . __('Serial: ') . '</td><td>' . $equipments->serial . '</td></tr>
                                            <tr><td>' . __('Lý do hỏng: ') . '</td><td>' . $equipments->reason . '</td></tr>
                                            <tr><td>' . __('Người báo hỏng: ') . '</td><td>' . $user->roles->first()->name . '</td></tr>
                                            <tr><td>' . __('Mã người báo hỏng: ') . '</td><td>' . $user->email . '</td></tr>
                                            <tr><td>' . __('Thời gian báo hỏng: ') . '</td><td>' . $equipments->date_failure . '</td></tr>
                                        </tbody>
                                    </table>
                                </div>';
                    //$data = array( 'email' =>'phongvt.ttb.bvkienan@gmail', 'from' => $user->email, 'content' => $content, 'title'=>$equipments->title );
                    $data = array('email' => 'dat.dt11122002@gmail.com', 'from' => $user->email, 'content' => $content, 'title' => $equipments->title);
                    Mail::send('mails.fail', compact('data'), function ($message) use ($data) {
                        $message->to($data['email'])
                            ->from($data['from'], '[Phòng VT TBYT]')
                            ->subject('Thiết bị báo hỏng ' . $data['title']);
                    });
                    $roles = ['admin', 'nvkp', 'Nvphc', 'Ddt', 'tphc'];
                    $array_user = User::role($roles)->pluck('id')->toArray();
                    if ($array_user != null) {
                        foreach ($array_user as $key => $value) {
                            $user = User::findOrFail($value);
                            $user->notify(new ReportFailureNotifications($equipments));
                        }
                    }
                    activity()->causedBy(Auth::user())->performedOn($equipments)->withProperties(['attributes' => $equipments])->log($equipments->status);
                    return response()->json([
                        'status' => '200',
                        'message' => 'Equipment was broken successfully'
                    ]);
                };
            } else {
                return response()->json([
                    'status_code' => 403,
                    'message' => 'User does not have permission'
                ],403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error Has Broken',
                'error' => $e,
            ],500);
        }
    }
}
