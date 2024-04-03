<?php

namespace App\Http\Controllers\backends;

use App\Models\User;
use App\Models\Provider;
use App\Models\Equipment;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\Department_User;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {

        $user = Auth::user();
        if ($user->can('users.show_all')) {
            $s = $request->s;
            $department_id = isset($request->department_id) ? $request->department_id : '';
            $role = $request->role;
            $departments = Department::select('id', 'title')->get();
            $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $users = User::with('user_department', 'departments', 'roles');
            if ($role != "") $users = $users->role($role);
            if ($s != "") $users = $users->where(function ($query) use ($s) {
                $query->where('name', 'like', '%' . $s . '%')
                    ->orWhere('displayname', 'like', '%' . $s . '%');
            });
            if ($department_id != "") $users = $users->where('department_id', $department_id);
            //if ($request->department_id != "") $users = Department::find($request->department_id)->users();
            //dd($users);
            $users = $users->latest()->paginate($number);
            $total = $users->total();
            $data = [
                'users' => $users,
                's' => $s,
                'department_id' => $department_id,
                'departments' => $departments,
                'number' => $number,
                'total' => $total,
                'role' => $role,
                'roles' => Role::all(),
            ];
            return view('backends.users.list', $data);
        }
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->can('users.create')) {
            $departments = Department::select('id', 'title')->get();
            $data = [
                'roles' => Role::all(),
                'departments' => $departments,
            ];
            return view('backends.users.create', $data);
        } else {
            abort(403);
        }
    }

    public function store(Request $request, CreatesNewUsers $creator)
    {
        event(new Registered($user = $creator->create($request->all())));

        if ($user) {
            $request->session()->flash('success', 'Create Successful!');
            if ($request->role != '') {
                $check_exist = Role::where('name', $request->role)->first();
                if ($check_exist) {
                    $user->assignRole($request->role);
                    //on success, insert user_id and department_id to the department_user table
                    if ($request->department_id_responsible) {
                        foreach ($request->department_id_responsible as $department_id_responsible) {
                            $department_user = new Department_User;
                            $department_user->user_id = $user->id;
                            $department_user->department_id = $department_id_responsible;
                            $department_user->save();
                        }
                    }
                } else {
                    $request->session()->flash('error', 'Role ' . $request->role . ' not exist!');
                    return redirect()->route('admin.users');
                }
            }
        } else {
            $request->session()->flash('error', 'Has error!');
            return redirect()->route('admin.user_create');
        }
        return redirect()->route('admin.users');
    }

    public function edit(Request $request, $id)
    {
        $users = Auth::user();
        if ($users->can('users.show_all')) {
            $user = User::findOrFail($id);
            $departments = Department::select('id', 'title')->get();
            $departments_responsible = Department_User::where('user_id', $id)->pluck('department_id')->toArray();
            //dd($departments_responsible);
            $data = [
                'user' => $user,
                'roles' => Role::all(),
                'departments' => $departments,
                'departments_responsible' => $departments_responsible
            ];
            return view('backends.users.edit', $data);
        } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rules = [
            'phone' => ['required', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'displayname' => 'required',
            'department_id' => 'required',
        ];
        $messages = [
            'phone.required' => 'Vui lòng nhập số điện thoại!',
            'phone.unique' => 'Điện thoại đã tồn tại!',
            'email.required' => 'Vui lòng nhập email!',
            'email.unique' => 'Email đã tồn tại!',
            'displayname.required' => 'Vui lòng nhập Tên hiển thị!',
            'department_id.required' => 'Vui lòng nhập bộ phận khoa phòng thuộc biên chế!',
        ];
        if ($request->password != '') {
            $rules['password'] = 'required|min:8|max:32';
            $rules['confirmPassword'] = 'required|same:password';
            $messages['password.required'] = 'Vui lòng nhập mật khẩu!';
            $messages['password.min'] = 'Mật khẩu tối thiểu là 8 ký tự!';
            $messages['password.max'] = 'Mật khẩu tối đa là 32 ký tự!';
            $messages['confirmPassword.required'] = 'Vui lòng xác nhận mật khẩu!';
            $messages['confirmPassword.same'] = 'Mật khẩu xác nhận không khớp!';
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->route('admin.user_edit', ['id' => $id])->withErrors($validator)->withInput();
        } else {
            $user->phone = $request['phone'];
            $user->email = $request['email'];
            $user->address = $request['address'];
            $user->image = $request['image'];
            $user->displayname = $request['displayname'];
            $user->department_id = $request['department_id'];
            $user->gender = $request['gender'];
            $user->birthday = $request['birthday'];
            $user->is_disabled = isset($request['is_disabled']) ? $request['is_disabled'] : '0';
            if ($request->password != '') $user->password = bcrypt($request->password);
            if ($user->save()) {
                if ($request->role != '') {
                    $check_exist = Role::where('name', $request->role)->first();
                    if ($check_exist) {
                        $user->syncRoles([$request->role]);
                        //on success, delete all related user_id
                        // and insert user_id, department_id to the department_user table
                        Department_User::where('user_id', $id)->delete();
                        if (isset($request->department_id_responsible)) {
                            foreach ($request->department_id_responsible as $department_id_responsible) {
                                $department_user = new Department_User;
                                $department_user->user_id = $user->id;
                                $department_user->department_id = $department_id_responsible;
                                $department_user->save();
                            }
                        }
                    } else {
                        $request->session()->flash('error', 'Role ' . $request->role . ' not exist!');
                        return redirect()->route('admin.users');
                    }
                }
                //dd($user);
                $request->session()->flash('success', 'Cập nhật thành công!');
                return redirect()->route('admin.users');
            } else {
                $request->session()->flash('error', 'Cập nhật thất bại!');
                return redirect()->route('admin.user_edit', ['id' => $id]);
            }
        }
    }

    public function delete(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->session()->flash('success', 'Xóa thành công!');
        $user->delete_user();
        return redirect()->route('admin.users');
    }

    public function deleteChoose(Request $request)
    {
        $items = explode(",", $request->items);
        if (count($items) > 0) {
            $request->session()->flash('success', 'Xóa thành công!');
            foreach ($items as $id) {
                User::find($id)->delete_user();
            }
        } else {
            $request->session()->flash('error', 'Xóa thất bại!');
        }
        return redirect()->route('admin.users');
    }

    public function indexActivity(Request $request)
    {
        $users = Auth::user();
        if ($users->can('users.diary')) {


            $keyword = isset($request->key) ? $request->key : '';
            $activitys_key = isset($request->activity_key) ? $request->activity_key : '';
            $department_id = isset($request->department_id) ? $request->department_id : '';
            $users_key = isset($request->user_key) ? $request->user_key : '';
            $user_name = User::select('id', 'name')->get();
            $data_link = array();
            $departments = Department::select('id', 'title')->get();
            $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $activities = Activity::query();
            if ($keyword != '') {
                $activities = $activities->whereHas(function ($query) use ($keyword) {
                    $query->where('description', 'like', '%' . $keyword . '%')
                        ->orWhere('created_at', 'like', '%' . $keyword . '%');
                });
            }
            if ($users_key != '') {
                $activities = $activities->where('causer_id', $users_key);
                $data_link['user_key'] = $users_key;
            }
            if ($activitys_key != '') {
                $activities = $activities->where('description', $activitys_key);
                $data_link['activity_key'] = $activitys_key;
            }
            if ($department_id != '') {
                $activities = $activities->whereHas('causer', function ($query) use ($department_id) {
                    $query->where('department_id', $department_id);

                    // $users = Department::find($department_id)->users()->get();
                    // $id = $users->pluck('id')->toArray();
                    // $query->whereIn('id', $id);
                });
                $data_link['department_id'] = $department_id;
            }
            $activities = $activities->orderBy('created_at', 'desc')->paginate($number);
            $total = $activities->total();
            return view('backends.users.activity', compact('activities', 'keyword', 'users_key', 'user_name', 'activitys_key', 'departments', 'department_id', 'total', 'number', 'data_link'));
        } else {
            abort(403);
        }
    }

    public function destroyActivity($id)
    {
        $activities = Activity::findOrFail($id);
        $activities->delete();
        return redirect()->back()->with('success', 'Xóa thành công');
    }

    public function deleteChooseActivity(Request $request)
    {
        $items = explode(",", $request->items);
        if (count($items) > 0) {
            $request->session()->flash('success', 'Xóa thành công!');
            Activity::destroy($items);
        } else {
            $request->session()->flash('error', 'Có lỗi!');
        }
        return redirect()->route('admin.index_activity');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'oldPass' => 'required',
            'newPass' => 'required',
            'confirmPass' => 'required',
        ], [
            'oldPass.required' => 'Bạn chưa nhập mật khẩu cũ!',
            'newPass.required' => 'Bạn chưa nhập mật khẩu mới!',
            'confirmPass.required' => 'Bạn chưa nhập lại mật khẩu!',
        ]);
        $user = Auth::User();
        $checkPass = password_verify($request->oldPass, $user->password);
        if ($checkPass) {
            $user->password = bcrypt($request->newPass);
            $user->save();
            $request->session()->flash('success', 'Thay đổi mật khẩu thành công!');
            return redirect()->route('admin.dashboard');
        } else {
            $request->session()->flash('error', 'Mật khẩu cũ không đúng!');
            return redirect()->back();
        }
    }

    public function createPermission(Request $request, $permission)
    {
        return Permission::firstOrCreate(['name' => $permission]);
    }

    public function yourProfile($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::select('id', 'title')->get();
        $departments_responsible = Department_User::where('user_id', $id)->pluck('department_id')->toArray();
        $data = [
            'user' => $user,
            'departments' => $departments,
            'departments_responsible' => $departments_responsible,
        ];
        return view('backends.users.profile', $data);
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rules = [
            'phone' => ['required', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'displayname' => 'required',
        ];
        $messages = [
            'phone.required' => 'Vui lòng nhập số điện thoại!',
            'phone.unique' => 'Điện thoại đã tồn tại!',
            'email.required' => 'Vui lòng nhập email!',
            'email.unique' => 'Email đã tồn tại!',
            'displayname.required' => 'Vui lòng nhập Tên hiển thị!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $user->phone = $request['phone'];
            $user->email = $request['email'];
            $user->address = $request['address'];
            $user->image = $request['image'];
            $user->displayname = $request['displayname'];
            $user->gender = $request['gender'];
            $user->birthday = $request['birthday'];
            if ($user->save() && $user->wasChanged()) {
                $request->session()->flash('success', 'Cập nhật thành công');
                return redirect()->back();
            } else {
                $request->session()->flash('error', 'Cập nhật thất bại!');
                return redirect()->back();
            }
        }
    }
}
