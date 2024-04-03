<?php
namespace App\Http\Controllers\backends\media;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\MediaCate;

class MediaCatAdminController extends Controller {
	public function index(Request $request){
		$user = Auth::user();
		$s = $request->s;
        $mediaCats = MediaCate::query();
        if($s != "") $mediaCats = $mediaCats->where('title','like','%'.$s.'%'); 
		$mediaCats = $mediaCats->withCount('media')->latest()->paginate(15);
		return view('backends.media.list_cat',['mediaCats'=>$mediaCats,'s'=>$s]);
	}

	public function store(){    	
		return view('backends.media.create_cat');
	}

	public function create(Request $request){
		$rules = [
            'title'=>'required|min:1',
        ];
        $messages = [
           	'title.required'=>__('Please input title!'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('storeMediaCatAdmin')->withErrors($validator)->withInput();;
        else:
			$object = MediaCate::create([
				'title'=>$request->title,
			]);
			if($object){
				$request->session()->flash('success', 'Add successful!');
				return redirect()->route('storeMediaCatAdmin');
			}else{
				$request->session()->flash('error', 'Has error!');
				return redirect()->route('storeMediaCatAdmin');
			}
		endif;
	}

	public function edit($id){ 
		$mediaCat = MediaCate::find($id);   	
		return view('backend.media.edit_cat',['category'=>$mediaCat]);
	}

	public function update(Request $request, $id){
		$rules = [
            'title'=>'required|min:1',
        ];
        $messages = [
           	'title.required'=>__('Please input title!')
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('editMediaCatAdmin',['id'=>$id])->withErrors($validator)->withInput();;
        else:
			$object = MediaCate::where('id', $id)->update([
				'title'=>$request->title,
			]);
			if($object){
				$request->session()->flash('success', 'Update Successful!');
			}else{
				$request->session()->flash('error', 'Has error!');
			}
			return redirect()->route('editMediaCatAdmin',['id'=>$id]);
		endif;
	}
	//change slug
     public function changeSlug(Request $request,$id){
        $message = 'error';
        if($request->ajax() && Auth::check()):
            MediaCate::where('id', $id)->update(['slug'=>$request->slug]);
            $message = $request->slug;
        endif;
        return $message;
    }
	public function delete(Request $request,$id) {
		$mediaCat = MediaCate::findOrFail($id);
		if($mediaCat->delete())
			$request->session()->flash('success', 'Delete Successful!');
		else
			$request->session()->flash('error', 'Has error!');
		return redirect()->route('mediaCatAdmin');
	}

	public function deleteChoose(Request $request){
		$items = explode(",",$request->items);
		if(count($items)>0){
			$request->session()->flash('success', 'Delete Successful!');
			MediaCate::destroy($items);
		}else{
			$request->session()->flash('error', 'Has error!');
		}
		return redirect()->route('mediaCatAdmin');
	}
}