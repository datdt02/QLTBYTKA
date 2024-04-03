<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

use Cviebrock\EloquentSluggable\SluggableScopeHelpers;



class Device extends Model {

    use SluggableScopeHelpers;

    use Sluggable;



    protected $table = "devices";


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
        'title','slug','image','cat_id','code', 'author_id'
    ];


    public function equipment(){
        return $this->belongsTo('App\Models\Cates','cat_id','id');
    }


    /*public function device_equipment(){
        return $this->belongsToMany('App\Models\Equipment');
    }*/
    public function device_equipment(){
        return $this->hasMany('App\Models\Equipment','devices_id','id');
    }

}
