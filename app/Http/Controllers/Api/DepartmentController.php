<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\DepartmentListRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DepartmentListRequest $request){

        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $departments = Department::query();
        if($user->can('department.read')){
            if($keyword != ''){
                $departments = $departments->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('code','like','%'.$keyword.'%')
                    ->orWhere('phone','like','%'.$keyword.'%')
                    ->orWhere('address','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }
        }else{
            $departments = $departments->where('id',$user->department_id);
            if($keyword != ''){
                $departments = $departments->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('code','like','%'.$keyword.'%')
                    ->orWhere('phone','like','%'.$keyword.'%')
                    ->orWhere('address','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }
        }
        $departments = $departments->orderBy('created_at', 'asc')->get();

        return response()->json([
            'status_code' => 200,
            'data' => $departments,
            'dataLength' => $departments->count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::findOrFail($id);
        $user = Auth::user();

        if($user->can('department.read') || $user->department_id == $id){
            return response()->json([
                'status_code' => 200,
                'data' => $department,
                'dataLength' =>$department->count(),
            ]);
        }else{
            return response()->json([
                'status_code' => 403,
                'message' => 'User does not permissions',
            ],403);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
