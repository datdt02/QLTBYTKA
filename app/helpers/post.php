<?php
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;

/**
* create a post
* @param $title,$excerpt,$image_id,$status,$type,$user_id
* @return $object
*/
if(! function_exists('create_post')) {
    function create_post($title,  $type, $status, $user_id, $excerpt=null, $image_id=null, $meta_key=null, $meta_value=null) {
        if(Auth::check())
            return Post::create([
                'title'=>$title,
                'excerpt'=>$excerpt,
                'image_id'=>$image_id,
                'meta_key'=>$meta_key,
                'meta_value'=>$meta_value,
                'status'=>$status,
                'type'=>$type,
                'user_id'=>$user_id
            ]);
        return ;
    }
}
/**
* update a post
* @param $title,$excerpt,$image_id,$status,$type,$user_id,$post_id
* @return $object
*/
if(! function_exists('update_post')) {
    function update_post($title, $status, $post_id, $excerpt=null, $image_id=null, $meta_key=null, $meta_value=null) {
        if(Auth::check())
            $post = Post::where('id',$post_id)
                ->update([
                        'title'=>$title,
                        'excerpt'=>$excerpt,
                        'image_id'=>$image_id,
                        'meta_key'=>$meta_key,
                        'meta_value'=>$meta_value,
                        'status'=>$status,
                    ]);
            return $post;
        return ;
    }
}
/**
* change slug post
* @return string
*/
if(! function_exists('change_slugPost')) {
    function change_slugPost($slug, $post_id) {
        if(Auth::check()){
            $result = Post::where('id',$post_id)->update(['slug'=>change_slug($slug)]);
            if($result==1){
                $post = Post::findOrFail($post_id);
                return $post->slug;
            }
        }
        return;
    }
}
/**
* delete post
* @param $post_id, $user_id
* @return int
*/
if(! function_exists('delete_post')) {
    function delete_post($id, $user_id) {
        if(Auth::check()):
            if(check_role(Auth::user()->role_id))
                $post = Post::findOrFail($id);
            else
                $post = Post::where('id',$id)->where('user_id',$user_id);
            return $post->delete();
        endif;
        return;
    }
}
/**
* delete choose posts
* @param $ids, $user_id
* @return int
*/
if(! function_exists('delete_posts')) {
    function delete_posts($ids, $user_id) {
        if(Auth::check()):
            $items = explode(",",$ids);
            if(count($items)>0){
                Post::destroy($items);
                return 'complete';
            }
        endif;
        return 'error';
    }
}
/**
* get post
* @param $id
* @return object
*/
if(! function_exists('get_post')) {
    function get_post($id) {
        return Post::findOrFail($id);
    }
}
/**
* get status post
* @return array
*/
if(! function_exists('get_statusPost')) {
    function get_statusPost() {
        return array(
            'public'=>'public',
            'draft'=>'draft',
            'trash'=>'trash',
        );
    }
}
/**
* get status request
* @return array
*/
if(! function_exists('get_statusRequest')) {
    function get_statusRequest() {
        return array(
            'sent'=>'Đã gửi',
            'confirmed'=>'Đã xác nhận',
        );
    }
}
if(! function_exists('get_statusClassPost')) {
    function get_statusClassPost($number) {
        return ($number==1)? "btn btn-block btn-outline-primary btn-xs" : "btn btn-block btn-outline-secondary btn-xs";
    }
}
/**
* get type post
* @return array
*/
if(! function_exists('get_type')) {
    function get_type() {
        return array(
            'page'=>'Trang',
            'blog'=>'blog',
            'blog_cat'=>'danh mục blog',
            'faqs'=>'faqs',
        );
    }
}
