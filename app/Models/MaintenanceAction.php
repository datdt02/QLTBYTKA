<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MaintenanceAction extends Model {

    protected $table = "maintenance_actions";
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = [
        'code', 'maintenance_id', 'type', 'author_id', 'note', 'created_date', 'date_of_action'
    ];

    // Casting format datetime for screated_datetart_date
    protected $casts = [
        'created_date' => 'date:Y-m-d',
        'date_of_action' => 'date:Y-m-d',
    ];

    /**
     * Get the author of action
     */
    public function author(){
        return $this->belongsTo('App\Models\User', 'author_id', 'id');
    }

    /**
     * Get the maintenance of action
     */
    public function maintenance(){
        return $this->belongsTo('App\Models\Maintenance', 'maintenance_id', 'id');
    }

    /**
     * Show type of action
     * @param MaintenanceAction::class
     * @return html of type
     */
    public function showType(){
        if($this->type == 'C' || strtoupper($this->type) == 'C') return '<span class="btn btn-sm btn-success">C</span>';
        if($this->type == 'I' || strtoupper($this->type) == 'I') return '<span class="btn btn-sm btn-warning">I</span>';
        if($this->type == 'M' || strtoupper($this->type) == 'M') return '<span class="btn btn-sm btn-danger">M</span>';
        return '<span class="btn btn-sm btn-success">C</span>';
    }
}