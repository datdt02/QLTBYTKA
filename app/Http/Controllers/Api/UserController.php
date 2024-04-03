<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index(Request $request)
    {

        try {

            $user = Auth::user();
            $department_key = isset($request->department_key) ? $request->department_key : '';
            $keyword = isset($request->keyword) ? $request->keyword : '';
            $users = User::query();

            if($user->can('users.show_all')){
                if($department_key != "")
                    $users = $users->where('department_id',$department_key);
                if($keyword != ''){
                    $users = $users->where(function ($query) use ($keyword) {
                    $query->where('displayname','like','%'.$keyword.'%')
                        ->orWhere('name','like','%'.$keyword.'%')
                        ->orWhere('email','like','%'.$keyword.'%');
                    });
                }
            } else {
                $users = $users->where('id',$user->id);
                if($keyword != ''){
                    $users = $users->where(function ($query) use ($keyword) {
                    $query->where('displayname','like','%'.$keyword.'%')
                        ->orWhere('name','like','%'.$keyword.'%')
                        ->orWhere('email','like','%'.$keyword.'%');
                    });
                }
            }

            $users = $users->orderBy('id', 'Desc')->get();


            return response()->json([
                'data' => $users,
                'dataLength' => $users->count()
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error',
                'error' => $error,
            ],500);
        }
    }

    public function show($id)
    {
        $users = User::findOrFail($id);
        $user = Auth::user();

        if($user->can('users.show_all') || $user->id == $id){
            return response()->json([
                'status_code' => 200,
                'data' => $users,
            ]);
        }else{
            return response()->json([
                'status_code' => 403,
                'message' => 'User does not permissions',
            ],403);
        }
    }

}



