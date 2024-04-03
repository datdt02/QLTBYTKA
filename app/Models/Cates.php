<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


use Cviebrock\EloquentSluggable\Sluggable;

use Cviebrock\EloquentSluggable\SluggableScopeHelpers;



class Cates extends Model {

    use SluggableScopeHelpers;

    use Sluggable;



    protected $table = "equipment_cates";

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
        'title','slug','image','code','author_id'
    ];



    public function device(){
        return $this->hasMany('App\Models\Device','cat_id','id');
    }

    public function cates_equipment(){
        return $this->belongsTo('App\Models\Equipment','cate_id','id');
    }

    public function providers(){
        return $this->belongsToMany('App\Models\Provider');
    }


}
