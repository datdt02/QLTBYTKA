<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller {
    public function index(Request $request){
        $user = Auth::user();
        if($user->can('options.roles')) {
            if($request->keyword != '') $roles = Role::where('display_name','like','%'.$request->keyword.'%')->latest()->paginate(12);
                else $roles = Role::latest()->paginate(12);
            $data = [
                'roles' => $roles,
                'keyword' => $request->keyword,
            ];
            return view('backends.roles.list',$data);
        }else{
          abort(403);
        }
    }

    public function create(Request $request){
        $user = Auth::user();
        if($user->can('options.roles')) {
            $data = [
                'permissions' => Permission::all()->sortBy('sidebar_id')->groupBy('group'),
            ];
            return view('backends.roles.create', $data);
        }else{
          abort(403);
        }
    }

    public function store(Request $request){
        $check = Role::where('name', $request->name)->first();
        if($check) {
            $request->session()->flash('error', 'This name had exist!');
            return redirect()->route('admin.role_create');
        }else{
            $role = Role::create(['name' => $request->name, 'display_name' => $request->display_name]);
            if($role && $request->permissions != null)
                $role->givePermissionTo($request->permissions);
            $request->session()->flash('success', 'Create Successful!');
            return redirect()->route('admin.roles');
        }
    }

    public function edit(Request $request, $id){
        $user = Auth::user();
        if($user->can('options.roles')) {
            $role = Role::findOrFail($id);
            $data = [
                'role' => $role,
                'permissions' => Permission::all()->sortBy('sidebar_id')->groupBy('group'),
            ];
            // echo '<pre>';
            // var_dump(Permission::all());
            // echo '</pre>';
            // die();
            return view('backends.roles.edit',$data);
        }else{
          abort(403);
        }
    }

    public function update(Request $request, $id){
        $role = Role::findOrFail($id);
        $role->display_name = $request->display_name;
        if($role->save()) {
            $request->session()->flash('success', 'Update Successful!');
            $role->syncPermissions($request->permissions);
        }else{
            $request->session()->flash('error', 'Has Error!');
        }
        return redirect()->route('admin.roles');
    }

    public function delete(Request $request, $id){
        $user = Auth::user();
        if($user->can('options.roles')) {
            $role = Role::findOrFail($id);
            $request->session()->flash('success', 'Delete Successful!');
            $role->delete();
            return redirect()->route('admin.roles');
        }else{
          abort(403);
        }
    }

    public function deleteChoose(Request $request){
        $user = Auth::user();
        if($user->can('options.roles')) {
            $items = explode(",",$request->items);
            if(count($items)>0){
                $request->session()->flash('success', 'Delete Successful!');
                Role::destroy($items);
            }else{
                $request->session()->flash('error', 'Has error!');
            }
            return redirect()->route('admin.roles');
        }else{
          abort(403);
        }
    }

}
