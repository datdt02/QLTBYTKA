<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
class Project extends Model {
    use SluggableScopeHelpers;
    use Sluggable;
    protected $table = "projects";
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = [
        'title','slug','procurement','decision','note','status','image','fromDate','toDate','author_id'
    ];
    public function equipment(){
        return $this->belongsTo('App\Models\Equipment','bid_project_id','id');
    }
}
