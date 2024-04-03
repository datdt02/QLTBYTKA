<?php
namespace App\Http\Controllers\backends\media;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Department;

class MediaAdminController extends Controller {
	public function index(Request $request){
		$user = Auth::user();
		$s = isset($request->s) ? $request->s : '';
		$department_id = isset($request->department_id) ? $request->department_id : '';
		$media = Media::query();
		if($user->can('media.show_all') && $user->can('media.read')){
			$departments = Department::select('id','title')->get();
        }else{
        	$departments = Department::where('id',$user->department_id)->select('id','title')->first();
            $media = $media->where('user_id',$user->id);
        }
        if($s != "") $media = $media->where('title','like','%'.$s.'%');
        if($department_id != "") $media = $media->whereHas('cates', function($query) use ($department_id) {
										   $query->where('media_media_cate.cate_id', $department_id);
										});
        $media = $media->latest()->paginate(10);
		return view('backends.media.list',['user'=>$user,'medias'=>$media,'s'=>$s,'departments'=>$departments,'department_id'=>$department_id,]);
	}
	//store
	public function store(){
		$user = Auth::user();
		if($user->can('media.show_all') && $user->can('media.read')){
			$mediaCats = getAllDepartment();
        }else{
        	$mediaCats = Department::where('id',$user->department_id)->select('id','title')->get();
        }
		return view('backends.media.create',['mediaCats'=>$mediaCats]);
	}
	//create
	public function create(Request $request){
		//dd($request);
		if($request->ajax()):
			$file = $request->file('file');
			$allowed = get_option('file_format')!= null ? explode(',', get_option('file_format')) : get_imageType();
			$file_fullName = $file->getClientOriginalName();
			$filename = pathinfo($file_fullName, PATHINFO_FILENAME);
			$extension = pathinfo($file_fullName, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed)):
				return response()->json(['message'=>'error','text'=>'File unformat, please just upload files: '.implode(", ",$allowed).'']);
			else:
				$category_ids = ($request->category)? explode(",",$request->category) : null;
				$media = create_media($filename,$extension,$category_ids,Auth::id());
				if($media){
					$file->move(public_path('uploads'),$media->slug.'.'.$extension);
					$media->path = $media->slug.'.'.$extension;
					$media->save();
					if($category_ids != null) $media->cates()->attach($category_ids);
					return response()->json(['message'=>'success','text'=>'Create Successful!']);
				}
			endif;
			return response()->json(['message'=>'error','text'=>'Has error!']);
		endif;
	  }
	//edit
	public function edit($id){ 
		$user = Auth::user();
		$media = Media::find($id);
		if($user->can('media.show_all') && $user->can('media.read')){
			$mediaCats = getAllDepartment();
        }else{
        	$mediaCats = Department::where('id',$user->department_id)->select('id','title')->get();
        }
		return view('backends.media.edit',['media'=>$media,'mediaCat'=>$mediaCats]);
	}
	//update
	public function update(Request $request, $id){
		$rules = [
            'title'=>'required|min:1',
        ];
        $messages = [
           	'title.required'=>'Please input title!',
        ];
        $media = Media::find($id);
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('storeMediaCatAdmin')->withErrors($validator)->withInput();;
        else:
			$object = update_media($request->title,$request->alt,$request->categories,$id);
			if($object){
				$media->cates()->sync($request->medias);
				$request->session()->flash('success', 'Update Successful!');
			}else{
				$request->session()->flash('error', 'Has error!');
			}
			return redirect()->route('editMediaAdmin',['id'=>$id]);
		endif;
	}
	//change slug
     public function changeSlug(Request $request,$id){
        $message = 'error';
        if($request->ajax() && Auth::check()):
            Media::where('id', $id)->update(['slug'=>$request->slug]);
            $message = $request->slug;
        endif;
        return $message;
	}
	//delete
	public function delete(Request $request,$id) {
		$media = delete_media($id);
		if($media)
			$request->session()->flash('success', 'Delete Successful!');
		else
			$request->session()->flash('error', 'Has error!');
		return redirect()->route('mediaAdmin');
	}
	//delete choose
	public function deleteChoose(Request $request){
		$items = explode(",",$request->items);
		if(count($items)>0){
			delete_mediaChoose($items);
			$request->session()->flash('success', 'Delete Successful!');
		}else{
			$request->session()->flash('error', 'Has error!');
		}
		return redirect()->route('mediaAdmin');
	}
	 //load media
	 public function loadMediaPopup(Request $request){
	 	if(isset($request->multiple) && $request->multiple == 'multiple') {
	 		$chosen = isset($request->chosen) && $request->chosen != '' ? explode(',',$request->chosen) : array();
	 		return get_mediaLibrary(45, 'multiple', $chosen);
	 	}else return get_mediaLibrary(45);
	}
	
	//search key
	public function filterMediaPopup(Request $request){
		if(isset($request->multiple) && $request->multiple == 'multiple') {
			$chosen = isset($request->chosen) && $request->chosen != '' ? explode(',',$request->chosen) : array();
			return filter_mediaLibrary($request->s,$request->catId, 'multiple', $chosen);	
		}else return filter_mediaLibrary($request->s,$request->catId);
	}
	//load more media
	public function loadMorePagePopup(Request $resquest){
		if(isset($request->multiple) && $request->multiple == 'multiple'){
			$chosen = isset($request->chosen) && $request->chosen != '' ? explode(',',$request->chosen) : array();
			return load_moreMediaLibrary($request->s, $request->catId, $request->current, 'multiple', $chosen);
		}else return load_moreMediaLibrary($request->s, $request->catId, $request->current);
	}

	public function uploadSummer(Request $request){
		if($request->ajax()):
			$file = $request->file('file');
			$allowed = get_imageType();
			$file_fullName = $file->getClientOriginalName();
			setlocale(LC_ALL, 'ko_KR.eucKR');
			$filename = han2eng(pathinfo($file_fullName, PATHINFO_FILENAME));
			$extension = pathinfo($file_fullName, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed)):
				return response()->json(['message'=>'error','text'=>'File unformat, please just upload files: '.implode(", ",$allowed).'']);
			else:
				$category_ids = ($request->category)? explode(",",$request->category) : null;
				$media = create_media($filename,$extension,$category_ids,Auth::id());
				if($media){
					$file->move(public_path('uploads'),$media->slug.'.'.$extension);
					$media->path = $media->slug.'.'.$extension;
					$media->save();
					return response()->json(['message'=>'success','file'=>$media->path]);
				}
			endif;
			return response()->json(['message'=>'error','text'=>'Has error!']);
		endif;
	}

	public function createMulti(Request $request){
		$files = $request->file('file');
		$allowed = explode(',', get_option('file_format'));
		$count = 0;
		foreach ($files as $file) {
			$file_fullName = $file->getClientOriginalName();
			$filename = pathinfo($file_fullName, PATHINFO_FILENAME);
			$extension = pathinfo($file_fullName, PATHINFO_EXTENSION);
			if (in_array(strtolower($extension), $allowed)):			
				$category_ids = ($request->category) ? explode(",",$request->category) : null;
				$media = create_media($filename,$extension,$category_ids,Auth::id());
				if($media){
					$file->move(public_path('uploads'),$media->slug.'.'.$extension);
					$media->path = $media->slug.'.'.$extension;
					$media->save();
					if($category_ids != null) $media->cates()->attach($category_ids);
					// return response()->json(['message'=>'success','text'=>'Create Successful!']);
				}
			endif;
		}
		if($count == 0) return response()->json(['message'=>'error','text'=>'File unformat, please just upload files: '.implode(", ",$allowed).'']);
			else return response()->json(['message'=>'success','text'=>'Create '.$count.' files successful!']);
	}
}