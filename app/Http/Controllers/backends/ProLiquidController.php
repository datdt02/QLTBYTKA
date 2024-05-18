<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Eqproperty;
use App\Models\Provider;
use App\Models\User;
use App\Models\Department;
use App\Models\Liquidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportEquipmentWaitingLiquidation;
use App\Repositories\Liquidation\LiquidationRepository;
use App\Repositories\Liquidation\LiquidationRepositoryInterface;
use App\Notifications\LiquidationNotifications;
use App\Notifications\PublicLiquiNotifications;

class ProLiquidController extends Controller
{
    public $liquidation;
    public function __construct(LiquidationRepositoryInterface $liquidation) {
        $this->liquidation = $liquidation;
    }
	public function index(Request $request) {
        $user = Auth::user();
	 	$eqliquis = Eqproperty::with('eqproperty_department','liquidations');

        //equipments that have "waiting" status in table liquidations
        $equipment_waiting_in_liquidation = Liquidation::where('status', 'waiting')->get();

	 	$keyword = isset($request->key) ? $request->key : '';
	 	$department_id = isset($request->department_id) ? $request->department_id : '';
	 	if($keyword != ''){
            $eqliquis = $eqliquis->where(function ($query) use ($keyword) {
            $query->where('title','like','%'.$keyword.'%')
                ->orWhere('code','like','%'.$keyword.'%')
                ->orWhere('model','like','%'.$keyword.'%')
                ->orWhere('serial','like','%'.$keyword.'%')
                ->orWhere('manufacturer','like','%'.$keyword.'%');
            });
        }

        if($user->can('proliquid.read')){
            $departments = Department::select('id','title')->get();
            if($department_id != ''){
                $eqliquis = $eqliquis->where('department_id',$department_id);
            }

        }else{
            $departments = Department::where('id',$user->department_id)->select('id','title')->get();
            $eqliquis = $eqliquis->where('department_id',$user->department_id);
            if($department_id != ''){
                $eqliquis = $eqliquis->where('department_id',$department_id);
            }
        }

        $eqliquis = $eqliquis->whereIn('status',['inactive','liquidated'])->where('amount','>', 0)->orderBy('created_at', 'desc')->paginate(10);
	 	$data=[
	 		'eqliquis'=>$eqliquis,
	 		'keyword'=>$keyword,
	 		'department_id'=>$department_id,
	 		'departments'=>$departments,
            'equipment_waiting_in_liquidation' => $equipment_waiting_in_liquidation,
	 	];
		return view('backends.proliquis.list',$data);
	}
    public function store(Request $request, $equip_id){
        $rules = [
            'amount'=>'required|min:0',
            'reason'=>'required',
        ];
        $messages = [
            'reason.required'=>'Vui lòng nhập lý do!',
            'amount.required'=>'Vui lòng nhập số lượng !',
            'amount.min'=>'Số lượng không được nhỏ hơn 0!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('proliquis.index')->withErrors($validator)->withInput();
        else:
            $equipment = Eqproperty::findOrFail($equip_id);
            /*$amount = $equipment->liquidations->where('status','waiting')->where('amount', '!=', null)->sum('amount');
            $remaining_amount = $equipment->amount - $amount;
            dd(intval($remaining_amount));*/
            if($request->amount <= $equipment->remaining_amount()):
                $request['user_id']= Auth::id();
                $request['equipment_id']= $equip_id;
                $atribute = $request->all();
                $liquidation = Liquidation::create($atribute);
                //notify
                $array_user = getUserPhcToNotify($equipment->id);
                    if($array_user != null){
                        foreach ($array_user as $key => $value) {
                            $user = User::findOrFail($value);
                            $user->notify(new LiquidationNotifications($liquidation));
                        }
                }
                if($equipment->wasChanged('status')){
                    $equipment['status'] = 'liquidated';
                    $equipment->update($request->only('status'));
                }
                return redirect()->route('proliquis.index')->with('success','Tạo phiếu đề nghị thanh lý thành công');
            else:
                return redirect()->route('proliquis.index')->with('error','Tạo phiếu đề nghị thanh lý thất bại');
            endif;
        endif;
    }
    public function listLiqui(Request $request, $equip_id) {
        $user = Auth::user();
        if($user->can('liquidation.read')){
            $equipment = Eqproperty::findOrFail($equip_id);
            // dd($equipment);
            $data = [
                'equipment'         => $equipment,
                'liquidations'      => $equipment->liquidations->sortByDesc('created_at')->simplePaginate(10),
            ];
            return view('backends.proliquis.list-lq',$data);
        }else{
            abort(403);
        }
    }
    public function update(Request $request, $equip_id, $liqui_id){
        $equipments = Eqproperty::findOrFail($equip_id);
        $liquidations = Liquidation::findOrFail($liqui_id);
        $liquidations['status']  = "liquidated";
        $liquidations['person_up']  = Auth::id();
        $liquidations->save();
        if($liquidations->status == 'liquidated'){
            //notify
            // $user= User::where('id',$liquidations->user_id)->first();
            // $roles = [$user->roles->first()->name];
            // $array_user = User::role($roles)->pluck('id')->toArray();

            $array_user = getUserPhcToNotify($equipments->id);
                if($array_user != null){
                    foreach ($array_user as $key => $value) {
                        $user = User::findOrFail($value);
                        $user->notify(new PublicLiquiNotifications($liquidations));
                    }
            }

        }
        if($liquidations){
            $equipments['amount']  = $equipments->amount - $liquidations->amount;
            $equipments->save();
            if($liquidations->wasChanged())
                return redirect()->back()->with('success','Cập nhật thành công');
            else
                return redirect()->back();
        }else{
            return redirect()->back()->with('error','Cập nhật không thành công');
        }
    }
    public function destroy($equip_id, $id){
        $user = Auth::user();
        $liquidation = Liquidation::findOrFail($id);
        if ($user->can('proliquid.delete')) {
            $liquidation->delete();
            \DB::table('notifications')
            ->where('type','App\Notifications\LiquidationNotifications')
            ->orWhere('type','App\Notifications\PublicLiquiNotifications')
            ->where('data->id',intval($id))
            ->delete();
            return redirect()->route('proliquis.listLiqui',['equip_id'=>$equip_id])->with('success','Xóa thành công');
        }else{
          abort(403);
        }
        // $this->liquidation->destroy($id);
        // return redirect()->route('eqliquis.listLiqui',['equip_id'=>$equip_id])->with('success','Xóa thành công');
    }
    public function exportLiquidation(Request $request) {
        return Excel::download(new ExportEquipmentWaitingLiquidation, 'Những thiết bị chờ thanh lý ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
}
