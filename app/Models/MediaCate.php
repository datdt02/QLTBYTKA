<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class MediaCate extends Model {
    use Sluggable;
    protected $table = "media_cates";
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = [
        'title','slug','content'
    ];


    /**
     * Get medias of cate
     */
    public function media() {
        return $this->belongsToMany('App\Models\Media', 'media_media_cate', 'cate_id', 'media_id');
    }

}
