<?php
namespace App\Http\Controllers\frontends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;
// use App\Page;
// use App\Post;
// use App\Faq;
// use App\Notice;

class PageController extends Controller
{
	public function index(){
        $page = 1;
        $title = 'icar';
		return view('auth.login');
	}

    // public function faqs(){
    // 	$page = Page::findOrFail(19);
    // 	$title = Post::select('title')->where('id',$page->post_id)->first();
    // 	$title = $title ? $title->title : 'Page Title';
    // 	$faqs = Faq::latest()->paginate(10);
    // 	return view('frontend.pages.faqs',['page'=>$page, 'title'=>$title, 'faqs'=>$faqs]);
    // }
}
