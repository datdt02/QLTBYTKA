<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SupplieDevice extends Model {


    protected $table = "supplies_devices";

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = [
        'supplie_id',
        'device_id',
        'amount',
    ];

    

}
