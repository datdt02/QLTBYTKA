<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidation extends Model {

    protected $table = "liquidations";


    /**

     * Return the sluggable configuration array for this model.

     *

     * @return array

     */

    protected $fillable = [

        'equipment_id',

        'amount',

        'reason',

        'user_id',

        

    ];


    public function user(){

        return $this->belongsTo('App\Models\User', 'user_id', 'id');

    }

    public function equipment(){

        return $this->belongsTo('App\Models\Equipment', 'equipment_id', 'id');

    }
    public function eqproperty(){

        return $this->belongsTo('App\Models\Eqproperty', 'equipment_id', 'id');

    }

   

}

