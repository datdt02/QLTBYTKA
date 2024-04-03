<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BallotSupplie extends Model {


    protected $table = "ballots_supplies";

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = [
        'ballot_id',
        'supplie_id',
        'amount',
    ];

    

}
