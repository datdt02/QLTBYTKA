<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class NotificationController extends Controller {
	public function index(Request $request) {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('read_at','asc')->orderBy('created_at','desc')->paginate(15);
        $total = $user->unreadNotifications()->groupBy('notifiable_type')->count();
        return view('backends.notice.list',compact('notifications','user','total'));
	}
    public function update($id){
        $notice = \DB::table('notifications')
                ->where('id',$id)
                ->update(['read_at' => now()]);
        return redirect()->back()->with('success','Đã xem thông báo này !!!');
    }
    public function destroy($id){
        $notice = \DB::table('notifications')
                ->where('id',$id)
                ->delete();
        return redirect()->back()->with('success','Xóa thành công');
    }
    public function readAll(){
        $notice = \DB::table('notifications')
                ->update(['read_at' => now()]);
        return redirect()->back()->with('success','Đã đọc tất cả thông báo');
    }
}
