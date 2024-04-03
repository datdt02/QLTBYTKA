<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HistoryInventories extends Model {

    protected $table = "history_inventories";

    protected $fillable = [
        'equipment_id', 'user_id', 'date', 'note','times'
    ];


    /**
     * Get the euipment of maintenance
     */
    public function equipment(){
        return $this->belongsTo('App\Models\Equipment', 'equipment_id', 'id');
    }

    /**
     * Get the author of maintenance
     */
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}