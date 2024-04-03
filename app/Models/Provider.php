<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Provider extends Model {

    use SluggableScopeHelpers;

    use Sluggable;

    protected $table = "providers";


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
        'title','slug','image','type','tax_code','fields_operation','note','repair','contact','email','address','phone','author_id'
    ];


    public function scopeProvider($query) {
        return $query->where('type', 'providers');
    }

    public function scopeMaintenance($query) {
        return $query->where('type', 'maintenances');
    }

    public function scopeRepair($query) {
        return $query->where('type', 'repairs');
    }

    public function equipment_cates(){
        return $this->belongsToMany('App\Models\Cates','providers_cat','provider_id','cat_id');
    }

    public function provider_equipment(){
        return $this->hasMany('App\Models\Equipment','provider_id','id');
    }

    public function provider_eqsupplie(){
        return $this->hasMany('App\Models\Eqsupplie','provider_id','id');
    }

    public function provider_maintenance(){
        return $this->hasMany('App\Models\Equipment','maintenance_id','id');
    }

    public function provider_repair(){
        return $this->hasMany('App\Models\Equipment','repair_id','id');
    }

    public function ballots(){
        return $this->hasMany('App\Models\EquipmentBallot','provider_id','id');
    }

    public function supplieBallots(){
        return $this->hasMany('App\Models\SupplieBallot','provider_id','id');
    }

}
