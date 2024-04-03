<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


use Cviebrock\EloquentSluggable\Sluggable;

use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Unit extends Model {

    use SluggableScopeHelpers;

    use Sluggable;

    protected $table = "units";

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
        'title','slug','image','author_id'
    ];


    public function unit_equipment(){
        return $this->hasMany('App\Models\Unit','unit_id','id');
    }


}
