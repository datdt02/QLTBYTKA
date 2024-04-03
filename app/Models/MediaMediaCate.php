<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MediaMediaCate extends Model {
    protected $table = "media_media_cate";


    protected $fillable = [
        'media_id', 'cate_id'
    ];

}
