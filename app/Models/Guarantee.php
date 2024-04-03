<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Guarantee extends Model {
    protected $table = "guarantees";
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = [
        'equipment_id','provider','time','note','content',
    ];
    
    public function equipments(){
        return $this->belongsTo('App\Models\Equipment','equipment_id','id');
    }
}
