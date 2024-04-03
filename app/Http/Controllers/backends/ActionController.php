<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Action;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ActionController extends Controller {
    public function index(Request  $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $eqrepairs = Action::query();
        if($user->can('eqrepair.read')){
            if($keyword != ''){
                $eqrepairs = $eqrepairs->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $eqrepairs = $eqrepairs->eqrepair()->paginate(10);
            return view('backends.eqrepairs.list', compact('eqrepairs','keyword',));
        }else{
            if($keyword != ''){
                $eqrepairs = $eqrepairs->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $eqrepairs = $eqrepairs->where('user_id',$user->id)->eqrepair()->paginate(10);
            return view('backends.eqrepairs.list', compact('eqrepairs','keyword',));
        }
    }
    public function create(){
        $user = Auth::user();
        if($user->can('eqrepair.create')){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.eqrepairs.create',compact('equipments'));
        }else{
            abort(403);
        }
    }
    public function store(Request  $request){
        $rules = [
            'user_id'=>'required',
            'equi_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('eqrepair.create')->withErrors($validator)->withInput();
        else:
        $atribute = $request->all();
        $atribute['type'] = 'equipment_repair';
        Action::create($atribute);
        return redirect()->route('eqrepair.index')->with('success','Thêm thành công');
        endif;
    }
    public function edit($id){
        $user = Auth::user();
        $eqrepairs = Action::findOrFail($id);
        if($user->can('update', $eqrepairs)){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.eqrepairs.edit',compact('eqrepairs','equipments'));
        }else{
          abort(403);
        }
    }
    public function update(Request  $request , $id){
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('eqrepair.edit',$id)->withErrors($validator)->withInput();
        else:
        $eqrepairs = Action::findOrFail($id);
        $atribute = $request->all();
        $eqrepairs->update($atribute);
        if($eqrepairs){
            if($eqrepairs->wasChanged())
                return redirect()->route('eqrepair.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('eqrepair.edit',$id);
        }else{
            return redirect()->route('eqrepair.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroy($id){
        $user = Auth::user();
        $eqrepairs = Action::findOrFail($id);
        if ($user->can('delete', $eqrepairs)) {
            $eqrepairs->delete();
            return redirect()->route('eqrepair.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
    public function indexPeriodic(Request  $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $periodics = Action::query();
        if($user->can('periodic.read')){
            if($keyword != ''){
                $periodics = $periodics->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $periodics = $periodics->periodic()->paginate(10);
            return view('backends.periodics.list', compact('periodics','keyword',));
        }else{
            if($keyword != ''){
                $periodics = $periodics->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $periodics = $periodics->where('user_id',$user->id)->periodic()->paginate(10);
            return view('backends.periodics.list', compact('periodics','keyword',));
        }
    }
    public function createPeriodic(){
        $user = Auth::user();
        if($user->can('periodic.create')){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.periodics.create',compact('equipments'));
        }else{
           abort(403); 
        }
    }
    public function storePeriodic(Request  $request)
    {
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('periodic.create')->withErrors($validator)->withInput();
        else:
        $atribute = $request->all();
        $atribute['type'] = 'periodic_maintenance';
        Action::create($atribute);
        return redirect()->route('periodic.index')->with('success','Thêm thành công');
        endif;
    }
    public function editPeriodic($id){
        $user = Auth::user();
        $periodics = Action::findOrFail($id);
        if($user->can('update', $periodics)){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.periodics.edit',compact('periodics','equipments'));
        }else{
          abort(403);
        }
    }
    public function updatePeriodic(Request  $request , $id)
    {
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('periodic.edit',$id)->withErrors($validator)->withInput();
        else:
        $periodics = Action::findOrFail($id);
        $atribute = $request->all();
        $periodics->update($atribute);
        if($periodics){
            if($periodics->wasChanged())
                return redirect()->route('periodic.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('periodic.edit',$id);
        }else{
            return redirect()->route('periodic.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroyPeriodic($id){
        $user = Auth::user();
        $periodics = Action::findOrFail($id);
        if ($user->can('delete', $periodics)) {
            $periodics->delete();
            return redirect()->route('periodic.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
    public function indexAccre(Request  $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $eqaccres = Action::query();
        if($user->can('eqaccre.read')){
            if($keyword != ''){
                $eqaccres = $eqaccres->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $eqaccres = $eqaccres->accre()->paginate(10);
            return view('backends.eqaccres.list', compact('eqaccres','keyword',));
        }else{
            if($keyword != ''){
                $eqaccres = $eqaccres->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $eqaccres = $eqaccres->where('user_id',$user->id)->accre()->paginate(10);
            return view('backends.eqaccres.list', compact('eqaccres','keyword',));
        }
    }
    public function createAccre(){
        $user = Auth::user();
        if($user->can('eqaccre.create')){ 
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.eqaccres.create',compact('equipments'));
        }else{
          abort(403);
        }
    }
    public function storeAccre(Request  $request){
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('eqaccre.create')->withErrors($validator)->withInput();
        else:
        $atribute = $request->all();
        $atribute['type'] = 'accreditation';
        Action::create($atribute);
        return redirect()->route('eqaccre.index')->with('success','Thêm thành công');
        endif;
    }
    public function editAccre($id){
        $user = Auth::user();
        $eqaccres = Action::findOrFail($id);
        if($user->can('update', $eqaccres)){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.eqaccres.edit',compact('eqaccres','equipments'));
        }else{
          abort(403);
        }
    }
    public function updateAccre(Request  $request , $id)
    {
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('eqaccre.edit',$id)->withErrors($validator)->withInput();
        else:
        $eqaccres = Action::findOrFail($id);
        $atribute = $request->all();
        $eqaccres->update($atribute);
        if($eqaccres){
            if($eqaccres->wasChanged())
            return redirect()->route('eqaccre.edit',$id)->with('success','Cập nhật thành công');
        else 
            return redirect()->route('eqaccre.edit',$id);
        }else{
            return redirect()->route('eqaccre.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroyAccre($id){
        $user = Auth::user();
        $eqaccres = Action::findOrFail($id);
        if ($user->can('delete', $eqaccres)) {
            $eqaccres->delete();
            return redirect()->route('eqaccre.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
    public function indexTransfer(Request  $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $transfers = Action::query();
        if($user->can('transfer.read')){
            if($keyword != ''){
                $transfers = $transfers->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $transfers = $transfers->transfer()->paginate(10);
            return view('backends.transfers.list', compact('transfers','keyword',));
        }else{
            if($keyword != ''){
                $transfers = $transfers->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $transfers = $transfers->where('user_id',$user->id)->transfer()->paginate(10);
            return view('backends.transfers.list', compact('transfers','keyword',));
        }
    }
    public function createTransfer(){
        $user = Auth::user();
        if($user->can('transfer.create')){ 
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.transfers.create',compact('equipments'));
        }else{
            abort(403);
        }
    }
    public function storeTransfer(Request  $request){
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('transfer.create')->withErrors($validator)->withInput();
        else:
        $atribute = $request->all();
        $atribute['type'] = 'transfers';
        $transfers = Action::create($atribute);
        return redirect()->route('transfer.index')->with('success','Thêm thành công');
        endif;
    }
    public function editTransfer($id){
        $user = Auth::user();
        $transfers = Action::findOrFail($id);
        if($user->can('update', $transfers)){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.transfers.edit',compact('transfers','equipments'));
        }else{
          abort(403);
        }
    }
    public function updateTransfer(Request  $request , $id){
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('transfer.edit',$id)->withErrors($validator)->withInput();
        else:
        $transfers = Action::findOrFail($id);
        $atribute = $request->all();
        $transfers->update($atribute);
        if($transfers){
            if($transfers->wasChanged())
                return redirect()->route('transfer.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('transfer.edit',$id);
        }else{
            return redirect()->route('transfer.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroyTransfer($id){
        $user = Auth::user();
        $transfers = Action::findOrFail($id);
        if ($user->can('delete', $transfers)) {
            $transfers->delete();
            return redirect()->route('eqaccre.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
    public function indexLiquida(Request  $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $liquidations = Action::query();
        if($user->can('liquidation.read')){
            if($keyword != ''){
                $liquidations = $liquidations->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $liquidations = $liquidations->liquida()->paginate(10);
            return view('backends.liquidations.list', compact('liquidations','keyword',));
        }else{
            if($keyword != ''){
                $liquidations = $liquidations->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $liquidations = $liquidations->where('user_id',$user->id)->liquida()->paginate(10);
            return view('backends.liquidations.list', compact('liquidations','keyword',));
        }
    }
    public function createLiquida(){
        $user = Auth::user();
        if($user->can('liquidation.create')){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.liquidations.create',compact('users','equipments'));
        }else{
          abort(403);
        }
    }
    public function storeLiquida(Request  $request){
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('liquidation.create')->withErrors($validator)->withInput();
        else:
        $atribute = $request->all();
        $atribute['type'] = 'liquidations';
        Action::create($atribute);
        return redirect()->route('liquidation.index')->with('success','Thêm thành công');
        endif;
    }
    public function editLiquida($id){
        $user = Auth::user();
        $liquidations = Action::findOrFail($id);
        if($user->can('update', $liquidations)){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.liquidations.edit',compact('liquidations','equipments'));
        }else{
          abort(403);
        }
    }
    public function updateLiquida(Request  $request , $id){
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('liquidation.edit',$id)->withErrors($validator)->withInput();
        else:
        $liquidations = Action::findOrFail($id);
        $atribute = $request->all();
        $liquidations->update($atribute);
        if($liquidations){
            if($liquidations->wasChanged())
                return redirect()->route('liquidation.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('liquidation.edit',$id);
        }else{
            return redirect()->route('liquidation.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroyLiquida($id){
        $user = Auth::user();
        $liquidations = Action::findOrFail($id);
        if ($user->can('delete', $liquidations)) {
            $liquidations->delete();
            return redirect()->route('liquidation.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
    public function indexGuarantee(Request  $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $guarantees = Action::query();
        if($user->can('guarantee.read')){
            if($keyword != ''){
                $guarantees = $guarantees->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $guarantees = $guarantees->guarantee()->paginate(10);
            return view('backends.guarantees.list', compact('guarantees','keyword',));
        }else{
            if($keyword != ''){
                $guarantees = $guarantees->where(function ($query) use ($keyword) {
                    $query->where('reason','like','%'.$keyword.'%')
                        ->orWhere('content','like','%'.$keyword.'%');
                    });
            }
            $guarantees = $guarantees->where('user_id',$user->id)->guarantee()->paginate(10);
            return view('backends.guarantees.list', compact('guarantees','keyword',));
        }
    }
    public function createGuarantee(){
        $user = Auth::user();
        if($user->can('guarantee.create')){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.guarantees.create',compact('users','equipments'));
        }else{
           abort(403); 
        }
    }
    public function storeGuarantee(Request  $request){
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('guarantee.create')->withErrors($validator)->withInput();
        else:
        $atribute = $request->all();
        $atribute['type'] = 'guarantee';
        $guarantees = Action::create($atribute);
        if($guarantees){
            return redirect()->route('guarantee.index')->with('success','Thêm thành công');
        }else {
            return redirect()->route('guarantee.index')->with('error','Thêm thành công');
        }
        endif;
    }
    public function editGuarantee($id){
        $user = Auth::user();
        $guarantees = Action::findOrFail($id);
        if($user->can('update', $guarantees)){
            $equipments = Equipment::select('id','title')->device()->get();
            return view('backends.guarantees.edit',compact('guarantees','equipments'));
        }else{
          abort(403);
        }
    }
    public function updateGuarantee(Request  $request , $id)
    {
        $rules = [
            'user_id'=>'required',
            'reason'=>'required',
            'content'=>'required',
            'equi_id'=>'required',
        ];
        $messages = [
            'user_id.required'=>'Please enter user',
            'reason.required'=>'Please enter reason',
            'content.required'=>'Please choose content',
            'equi_id.required'=>'Please enter equipment',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('guarantee.edit',$id)->withErrors($validator)->withInput();
        else:
        $guarantees = Action::findOrFail($id);
        $atribute = $request->all();
        $guarantees->update($atribute);
        if($guarantees){
            if($guarantees->wasChanged())
                return redirect()->route('guarantee.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('guarantee.edit',$id);
        }else{
            return redirect()->route('guarantee.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroyGuarantee($id){
        $user = Auth::user();
        $guarantees = Action::findOrFail($id);
        if ($user->can('delete', $guarantees)) {
            $guarantees->delete();
            return redirect()->route('guarantee.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
}