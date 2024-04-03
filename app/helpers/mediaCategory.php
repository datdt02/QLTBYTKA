<?php
use App\Models\MediaCate;
use App\Models\Media;

/**
* get all media categories
* @return $objects
*/
if(! function_exists('get_mediaCategoreis')) {
    function get_mediaCategoreis() {
        return MediaCate::select('id','title','slug')->latest()->get();
    }
}
/**
* get media category by id
* @param $cat_id
* @return $object
*/
if(! function_exists('get_mediaCat')) {
    function get_mediaCat($cat_id) {
        return MediaCate::where('id',$cat_id)->select('id','title')->first();
    }
}
/**
* count items
* @param $cat_id
* @return $object
*/
if(! function_exists('count_mediaCat')) {
    function count_mediaCat($cat_id) {
        return Media::whereRaw('JSON_CONTAINS(category_ids, \'["'.$cat_id.'"]\')')->count();
    }
}