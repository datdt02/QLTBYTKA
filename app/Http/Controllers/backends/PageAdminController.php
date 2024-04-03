<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Page;

class PageAdminController extends Controller {
	
    public function index(Request $request){
		$s = $request->s;
		$status = $request->status;
		$pages = Page::query();
        $pages = $pages->leftJoin('posts','posts.id','=','pages.post_id')
                        ->leftJoin('users','users.id','=','posts.user_id')
                        ->select('pages.id as id','title','slug', 'image_id', 'posts.created_at as created_at','post_id','users.name as author','status');
		if($status !="" && $s != ""){
			$pages = $pages->where('posts.title','like','%'.$s.'%')->where('posts.status',$status);
		}elseif($s != ""){
			$pages = $pages->where('posts.title','like','%'.$s.'%');
		}elseif($status!=""){
			$pages = $pages->where('posts.status',$status);
		}
		$pages = $pages->latest('pages.created_at')->paginate(15);
		return view('backends.pages.list',['pages'=>$pages,'s'=>$s,'status'=>$status]);
	}

	public function store(){
		return view('backends.pages.create');
	}

	public function create(Request $request){
		$rules = [
			'title'=>'required',
			'content'=>'required',
			'status'=>'required'
        ];
        $messages = [
			'title.required'=>'Please enter title',
			'content.required'=>'Please enter content',
			'status.required'=>'Please choose status'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('storePageAdmin')->withErrors($validator)->withInput();;
		else:
			$object = create_post($request->title, $request->excerpt, $request->thumbnail, $request->metaKey,$request->metaValue,'page', $request->status, Auth::id());
			if($object){
				$page = Page::create([
					'content'=>$request->content,
					'post_id'=>$object->id
				]);
				if($page){
					$request->session()->flash('success', 'create complete!');
				}else{
					delete_post($object->id);
					$request->session()->flash('error', 'create errors!');
				}
				return redirect()->route('storePageAdmin');
			}else{
				$request->session()->flash('error', 'create errors!');
				return redirect()->route('storePageAdmin');
			}
		endif;
	}

	public function edit($id){
		$page = Page::findOrFail($id);
		if($page){
			$page = Post::leftJoin('pages','pages.post_id','posts.id')
                ->where('posts.id',$id)
                ->select('posts.id as id','title','slug','excerpt','content','image_id','meta_key','meta_value','type','template','status','user_id')
                ->first();
			return view('backends.pages.edit',['page'=>$page]);
		}
		return redirect()->route('pageAdmin');
	}

	public function update(Request $request, $id){
		$rules = [
			'title'=>'required',
			'content'=>'required',
			'status'=>'required'
        ];
        $messages = [
			'title.required'=>'Please enter title',
			'content.required'=>'Please enter description',
			'status.required'=>'Please choose status'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('editPageAdmin',['id'=>$id])->withErrors($validator)->withInput();;
		else:
			$page = Page::findOrFail($id);
			$post = update_post($request->title,$request->excerpt, $request->thumbnail, $request->metaKey, $request->metaValue, $request->status, $page->post_id);
			if($post){
				$request->session()->flash('success', 'update complete!');
				$page = Page::where('id',$id)->update([
					'content'=>$request->content,
					'template'=>$request->template,
					]);
				return redirect()->route('editPageAdmin',['id'=>$id]);
			}else{
				$request->session()->flash('error', 'update errors!');
				return redirect()->route('editPageAdmin',['id'=>$id]);
			}
		endif;
	}
	//change slug
     public function changeSlug(Request $request,$id){
        $message = 'error';
		if($request->ajax()):
			$page = Page::findOrFail($id);
            $slug = change_slugPost($request->slug, $page->post_id);
            if(strlen($slug)>0) $message = $slug;
        endif;
        return $message;
    }
	public function delete(Request $request,$id) {
		$page = Page::findOrFail($id);
		if(delete_post($page->post_id,Auth::id()))
			$request->session()->flash('success', 'delete complete!');
		else
			$request->session()->flash('error', 'delete error!');
		return redirect()->route('pagesAdmin');
	}

	public function deleteChoose(Request $request){
		$result = delete_posts($request->items,Auth::id());
		if($result=="complete")
			$request->session()->flash('success', 'delete complete!');
		else
			$request->session()->flash('error', 'delete error!');
		return redirect()->route('pagesAdmin');
	}
}