<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SupplieBallot extends Model {

    protected $table = "supplie_ballots";

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
        'ballot','department_id','provider_id','user_id','date_vote','note','date_up','person_up','status'
    ];

    public function departments(){
        return $this->belongsTo('App\Models\Department','department_id','id');
    }

    public function providers(){
        return $this->belongsTo('App\Models\Provider','provider_id','id');
    }

    public function users(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function supplies(){
        return $this->belongsToMany('App\Models\Eqsupplie','ballots_supplies','ballot_id','supplie_id')->withPivot('amount','unit_price','department_id');
    }

}
