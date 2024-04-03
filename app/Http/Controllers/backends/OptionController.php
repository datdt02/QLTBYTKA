<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
class OptionController extends Controller {
    public function index(){
        $user = Auth::user();
        if($user->can('options.info')) {
            $array = array('logo', 'favicon', 'site_name', 'phone', 'address', 'email', 'hotline', 'website');
            $res = array();
            foreach ($array as $item) {
                $option = Option::firstOrCreate(['key'=>$item]);
                $res[$item] = $option->value;
            }
            $data = [
                'option' => $res,
            ];
            return view('backends.options.system',$data);
        }else{
          abort(403);
        }
    }
    public function update(Request $request){
        $array = array('logo', 'favicon', 'site_name', 'phone', 'address', 'email', 'hotline', 'website');
        foreach ($array as $item) {
            if($request->$item) Option::where('key', $item)->update(['value'=>$request->$item]);
        }
        $request->session()->flash('success', 'Cập nhật thành công !');
        return redirect()->route('admin.system');
    }

    public function config(){
        $user = Auth::user();
        if($user->can('options.config')) {
            $array = array('email_support', 'hotline_support', 'fanpage', 'file_format');
            $res = array();
            foreach ($array as $item) {
                $option = Option::firstOrCreate(['key'=>$item]);
                $res[$item] = $option->value;
            }
            $data = [
                'option' => $res,
            ];
            return view('backends.options.config',$data);
        }else{
          abort(403);
        }
    }
    public function configUpdate(Request $request){
        $array = array('email_support', 'hotline_support', 'fanpage','file_format');
        foreach ($array as $item) {
            if($request->$item) Option::where('key', $item)->update(['value'=>$request->$item]);
        }
        $request->session()->flash('success', 'Cập nhật thành công !');
        return redirect()->route('admin.config');
    }
}