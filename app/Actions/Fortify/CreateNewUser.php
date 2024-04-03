<?php
namespace App\Actions\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers {
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input) {
        // dd($input);
        $messages = [
            'name.required'=>__('Vui lòng nhập tên người dùng!'),
            'name.regex'=>__('This username not match type!'),
            'name.unique'=>__('Tên người dùng này đã tồn tại!'),
            'email.required'=>__('Vui lòng nhập Email của bạn!'),
            'email.email'=>__('Please input correct email type!'),
            'email.unique'=>__('Email này đã tồn tại!'),
            'password.required'=>__('Vui lòng nhập mật khẩu!'),
        ];
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ], $messages)->validate();
        $department_id = isset($input['department_id']) ? $input['department_id'] : null;
        $image = isset($input['image']) ? $input['image'] : null;
        $gender = isset($input['gender']) ? $input['gender'] : null;
        $birthday = isset($input['birthday']) ? $input['birthday'] : null;

        return User::create([
            'name' => $input['name'],
            'displayname' => $input['displayname'],
            'email' => $input['email'],
            'address' => $input['address'],
            'phone' => $input['phone'],
            'gender' => $gender,
            'birthday' => $birthday,
            'image' => $image,
            'department_id' => $department_id,
            'password' => Hash::make($input['password']),
        ]);
    }
}
