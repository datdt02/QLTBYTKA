<?php
use Illuminate\Support\Facades\Auth;
use App\Models\Media;
use App\Models\User;

/**
* image type
* @return array
*/
if(! function_exists('get_imageType')) {
    function get_imageType() {
        return array('gif','png','jpg','jpeg','JPEG','doc','xls','xlsm','svg','pdf','jfif','PNG','JPG', 'GIF','SVG');
    }
}
/**
* select media
* @param $title,$type,$category_ids,$user_id
* @return $object
*/
if(! function_exists('get_media')) {
    function get_media() {
        return Media::leftJoin('users','users.id','=','media.user_id')
                    ->select('media.title as title','media.path as path','media.created_at as created_at','users.name as author')
                    ->latest()->pageinate(15);
    }
}
/**
* create a media
* @param $title,$type,$category_ids,$user_id
* @return $object
*/
if(! function_exists('create_media')) {
    function create_media($title,$type,$category_ids,$user_id) {
        return Media::create([
            'title'=>$title,
            'alt'=>$title,
            'type'=>$type,
            'user_id'=>$user_id
        ]);
    }
}
/**
* update a media
* @param $title,$path,$type,$category_ids,$user_id,$id
* @return $object
*/
if(! function_exists('update_media')) {
    function update_media($title,$alt,$category_ids,$id) {
        $user = Auth::user();
        if($user->can('media.update'))
            return Media::where('id',$id)
            ->update([
                'title'=>$title,
                'alt'=>$alt,
            ]);
        else
            return Media::where('id',$id)
                ->where('user_id',Auth::id())
                ->update([
                    'title'=>$title,
                    'alt'=>$alt,
                ]);
    }
}
/**
* delete a media
* @param $id, $user_id
* @return $object
*/
if(! function_exists('delete_media')) {
    function delete_media($id) {
        $user = Auth::user();
        if($user->can('media.delete'))
            $media = Media::findOrFail($id);
        else
            $media = Media::select('id','path')->where('user_id',$user->id)->where('id',$id)->first();
        if($media){
            $img_path = public_path('uploads/').$media->path; 
            if(file_exists($img_path)) {unlink($img_path);}
            return $media->delete();
        }
        return ;
    }
}
/**
* delete media are choose
* @param $array
* @return $object
*/
if(! function_exists('delete_mediaChoose')) {
    function delete_mediaChoose($ids) {
        $user = Auth::user();
        if($user->can('media.read'))
            foreach($ids as $id){
                $media = Media::findOrFail($id);
                $img_path = public_path('uploads/').$media->path; 
                if(file_exists($img_path)) {unlink($img_path);}
                $media->delete();
            }
        else
            foreach($ids as $id){
                $media = Media::select('id','path')->where('user_id',$user->id)->where('id',$id)->first();
                $img_path = public_path('uploads/').$media->path; 
                if(file_exists($img_path)) {unlink($img_path);}
                $media->delete();
            }
        return;
    }
}
/**
* load media library
* @return $object
*/
if(! function_exists('get_mediaLibrary')) {
    function get_mediaLibrary($limit, $multiple=null, $chosen=array()) {
        if(Auth::check()):
            $user = Auth::user();
            $total = Media::count();
            if($user->can('media.read'))
                $medias = Media::limit($limit)->latest()->get();
            else
                $medias = Media::limit($limit)->where('user_id',$user->id)->latest()->get();
            if($medias):
                $message = "success";
                $html = '';
                if($multiple != 'multiple'):
                    foreach ($medias as $media):
                        $html .= view('parts.single-media', ['media'=>$media])->render();
                    endforeach;
                else:
                    foreach ($medias as $media):
                        $checked = in_array($media->id, $chosen) ? TRUE : FALSE;
                        $html .= view('parts.multi-media', ['media'=>$media, 'checked' => $checked])->render();
                    endforeach;
                endif;
                return ['message'=>'success','html'=>$html,'total'=>$total,'limit'=>$limit,'current'=>count($medias)];    
            endif;
            return ['message'=>'error','html'=>''];
        endif;
    }
}
/**
* filter media library
* @return $object
*/
if(! function_exists('filter_mediaLibrary')) {
    function filter_mediaLibrary($s, $cat_id, $multiple=null, $chosen=array()) {        
        $limit = 27;
        $total = Media::query();
        $medias = Media::query();
        if($s != '') $medias = $medias->where('title','like','%'.$s.'%');
        if($cat_id != '') {
            $cate_query = function ($query) use ($cat_id) {
                return $query->where('cate_id',$cat_id);
            };
            $medias = $medias->whereHas('cates', $cate_query);
            $total = $total->whereHas('cates', $cate_query);
        }
        $medias = $medias->offset(0)->limit($limit)->latest()->get();
        $total = $total->count();
        if($medias):
          $html = '';
          if($multiple != 'multiple'):
                foreach ($medias as $media):
                    $html .= view('parts.single-media', ['media'=>$media])->render();
                endforeach;
            else:
                foreach ($medias as $media):
                    $checked = in_array($media->id, $chosen) ? TRUE : FALSE;
                    $html .= view('parts.multi-media', ['media'=>$media, 'checked' => $checked])->render();
                endforeach;
            endif;
          return response()->json(['message'=>'success','html'=>$html,'total'=>$total,'limit'=>$limit,'current'=>count($medias)]);
        endif;
        return response()->json(['message'=>'error','html'=>'']);
    }
}
/**
* filter media library
* @return $object
*/
if(! function_exists('load_moreMediaLibrary')) {
    function load_moreMediaLibrary($s, $cat_id, $current, $multiple=null, $chosen=array()){        
        $message = "error";
        $limit = 27;
        $total = Media::query();
        $medias = Media::query();
        if($s != '') $medias = $medias->where('title','like','%'.$s.'%');
        if($cat_id != '') {
            $cate_query = function ($query) use ($cat_id) {
                return $query->where('cate_id',$cat_id);
            };
            $medias = $medias->with(['cates'=>$cate_query])
                        ->whereHas('cates', $cate_query);
            $total = $total->with(['cates'=>$cate_query])
                        ->whereHas('cates', $cate_query);
        }
        $total = $total->count();
        if($total > $current):
            $medias = $medias->offset(0)->offset($current)->limit($limit)->latest()->get();
            $current = $current + count($medias);
            $message = "success";
        endif;
        if($message=="success"){
            if($multiple != 'multiple'):
                foreach ($medias as $media):
                    $html .= view('parts.single-media', ['media'=>$media])->render();
                endforeach;
            else:
                foreach ($medias as $media):
                    $checked = in_array($media->id, $chosen) ? TRUE : FALSE;
                    $html .= view('parts.multi-media', ['media'=>$media, 'checked' => $checked])->render();
                endforeach;
            endif;
            return response()->json(['message'=>$message,'html'=>$html,'total'=>$total,'limit'=>$limit,'current'=>$current]);
        }
        return response()->json(['message'=>$message,'html'=>'']);
    }
}
