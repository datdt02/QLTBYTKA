<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Transfer extends Model {
    protected $table = "transfers";
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = [
        'equipment_id','user_id','department_id','content','time_move','image','note','amount','status','approver'
    ];
    public function transfer_user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function transfer_equipment(){
        return $this->belongsTo('App\Models\Equipment','equipment_id','id');
    }
    public function transfer_eqproperty(){
        return $this->belongsTo('App\Models\Eqproperty','equipment_id','id');
    }
    public function transfer_department(){
        return $this->belongsTo('App\Models\Department','department_id','id');
    }
}
