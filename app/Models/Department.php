<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;


class Department extends Model
{

    use SluggableScopeHelpers;
    use Sluggable;

    protected $table = "departments";

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
        'title', 'code', 'slug', 'phone', 'contact', 'email', 'address', 'user_id', 'author_id', 'nursing_id', 'image', 'browser', 'browser_day'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'department_user', 'department_id', 'user_id');
    }

    public function ballots()
    {
        return $this->hasMany('App\Models\EquipmentBallot', 'department_id', 'id');
    }

    public function supplieBallots()
    {
        return $this->hasMany('App\Models\SupplieBallot', 'department_id', 'id');
    }

    public function department_users()
    {
        return $this->belongsTo('App\Models\User', 'nursing_id', 'id');
    }

    public function department_equipment()
    {
        return $this->hasMany('App\Models\Equipment', 'department_id', 'id');
    }
    public function department_eqproperty()
    {
        return $this->hasMany('App\Models\Eqproperty', 'department_id', 'id');
    }

    public function department_user()
    {
        return $this->hasMany('App\Models\User');
    }

    public function department_transfer()
    {
        return $this->hasMany('App\Models\Transfer', 'department_id', 'id');
    }
    public function inventories()
    {
        return $this->hasManyThrough(
            'App\Models\Inventory',
            'App\Models\Equipment',
            'department_id',
            'equipment_id',
            'id'
        );
    }
    public function history_inventories()
    {
        return $this->hasManyThrough(
            'App\Models\HistoryInventories',
            'App\Models\Equipment',
            'department_id',
            'equipment_id',
            'id'
        );
    }
    public function attachments()
    {
        return $this->morphToMany('App\Models\Media', 'mediable')->wherePivot('type', 'inventory');
    }

}
