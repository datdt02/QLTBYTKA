<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class InfrastructureLiquidation extends Model
{
    use HasFactory;
    use Sluggable;
    use SluggableScopeHelpers;

    protected $table = "liquidation_tickets";

    public function sluggable(): array

    {

        return [

            'slug' => [

                'source' => 'title'

            ]

        ];

    }
    protected $fillable = [
        'equipment_id',
        'creator_id',
        'status',
        'code',
        'created_date',
        'created_note',
        'approval_date',
        'approver_id',
        'liquidation_note',
        'approver_note',
        'price',
        'evaluation_status'
    ];
}
