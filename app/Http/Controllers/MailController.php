<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Department;
use Laravel\Jetstream\Rules\Role;

class MailController extends Controller
{
    //
    public function show($equitment_id){
        return getUserToMail($equitment_id);
     }
     public function index($equipment_id)
     {
        $users = User::query();
        $orRole = ['BGD', 'nvkp', 'TK'];
        $d = Equipment::find($equipment_id);
        $user = User::where('department_id',$d->department_id)->pluck('id');
        $email = $users->role($orRole)->whereIn('id', $user);

        return $email->pluck('email');
        // return $email;
     }
     public function check(){
        $approved = User::whereHas("roles", function ($q) {
            $q->where("name", "TPVT");
        })->first();
        return $approved->displayname;
     }
}
