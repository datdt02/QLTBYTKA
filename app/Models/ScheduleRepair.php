<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ScheduleRepair extends Model {
    protected $table = "schedule_repairs";
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = [
        'equipment_id',
        'code',
        'repair_date',
        'pre_corrected',
        'provider_id',
        'expected_cost',
        'acceptance',
        'completed_repair',
        'repaired_status',
        'actual_costs',
        'documents',
        'planning_date',
        'user_id',
        'representative',
        'update_date',
        'person_up',
        'approved',
        'date_failure',
        'reason',
    ];
    public function provider(){
        return $this->belongsTo('App\Models\Provider', 'provider_id', 'id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function equipment(){
        return $this->belongsTo('App\Models\Equipment', 'equipment_id', 'id');
    }
    public function eqproperty(){
        return $this->belongsTo('App\Models\Eqproperty', 'equipment_id', 'id');
    }
    public function attachments() {
        return $this->morphToMany('App\Models\Media', 'mediable')->wherePivot('type','repair')->withPivot('type');
    }
}
