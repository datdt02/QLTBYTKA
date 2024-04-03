<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class InfrastructureGroup extends Model
{
    use HasFactory;
    use SluggableScopeHelpers;

    use Sluggable;



    protected $table = "infrastructure_units";

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
        'alias'
    ];
}
