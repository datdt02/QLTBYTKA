<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Support\Str;

class Media extends Model {
    use SluggableScopeHelpers;
    use Sluggable;
    protected $table = "media";
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
        'title','slug','alt','type','path','content','user_id'
    ];


    /**
     * Get cates of media
     */
    public function cates() {
        return $this->belongsToMany('App\Models\Department', 'media_media_cate', 'media_id', 'cate_id');
    }

    /**
     * Get author of media
     */
    public function author() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * Get all of the equipments that are assigned this media-attachment.
     */
    public function equipments() {
        return $this->morphedByMany('App\Models\Equipment', 'mediable');
    }

    /**
     * get feature path of media
     */
    public function getFeature($width='150', $height='100') {
        if(in_array(Str::lower($this->type), ['jpg','png','jpeg','bmp','ico'])) return url('/').'/image/'.$this->path.'/150/100';
        if(in_array($this->type, ['doc','docx'])) return url('images-temp').'/docx.png';
        if(in_array($this->type, ['xls','xlsx'])) return url('images-temp').'/xls.png';
        if(in_array($this->type, ['ppt','pptx'])) return url('images-temp').'/ppt.png';
        if(in_array($this->type, ['pdf'])) return url('images-temp').'/pdf.jpg';
        if(in_array($this->type, ['txt'])) return url('images-temp').'/txt.png';
        if(in_array($this->type, ['svg'])) return url('uploads').'/'.$media->path;

        return url('images-temp').'/attachment.png';
    }

    /**
     * get feature path of media
     */
    public function getLink() {
        return url('uploads').'/'.$this->path;
    }

}
