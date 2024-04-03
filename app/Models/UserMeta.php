<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model {
    protected $table = "user_metas";

    protected $fillable = [
        'key', 'value'
    ];

    /**
     * Get the user of meta
     */
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id' , 'id');
    }
}
