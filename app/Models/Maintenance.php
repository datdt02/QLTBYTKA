<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model {

    protected $table = "maintenances";

    protected $fillable = [
        'title', 'equipment_id', 'approve_id', 'author_id', 'start_date', 'note', 'status', 'frequency','provider'
    ];

    // Casting format datetime for start_date
    // protected $casts = [
    //     'start_date' => 'datetime:Y-m-d H:i:s',
    // ];

    /**
     * Get the euipment of maintenance
     */
    public function equipment(){
        return $this->belongsTo('App\Models\Equipment', 'equipment_id', 'id');
    }

    /**
     * Get the author of maintenance
     */
    public function author(){
        return $this->belongsTo('App\Models\User', 'author_id', 'id');
    }

    /**
     * Get the approve user of maintenance
     */
    public function approve_user(){
        return $this->belongsTo('App\Models\User', 'approve_id', 'id');
    }

    /**
     * Get all actions user of maintenance
     */
    public function actions(){
        return $this->hasMany('App\Models\MaintenanceAction', 'maintenance_id', 'id');
    }

    /**
     * Check action in date
     * @param $date - format Y-m-d
     * @return action_type OR false
     */
    public function actionInDate($date){
        $res = $this->actions()->whereDate('date_of_action', $date)->select('id', 'type', 'maintenance_id', 'code', 'author_id','note')->first();
        return $res ? $res : false;
    }
}
