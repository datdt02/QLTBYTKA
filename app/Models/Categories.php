<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Categories extends Model
{
    use HasFactory;
    use SluggableScopeHelpers;

    use Sluggable;



    protected $table = "infrastructure_cates";

    public function sluggable(): array

    {

        return [

            'slug' => [

                'source' => 'title'

            ]

        ];

    }

    protected $fillable = [
        'name',
        'alias',
        'group_id'
    ];
}
