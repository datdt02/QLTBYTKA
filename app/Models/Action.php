<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
class Action extends Model {
    use SluggableScopeHelpers;
    use Sluggable;
    protected $table = "actions";
    public function scopeEqrepair($query) {
        return $query->where('type', 'equipment_repair');
    }
    public function scopePeriodic($query) {
        return $query->where('type', 'periodic_maintenance');
    }
    public function scopeAccre($query) {
        return $query->where('type', 'accreditation');
    }
    public function scopeTransfer($query) {
        return $query->where('type', 'transfers');
    }
    public function scopeGuarantee($query) {
        return $query->where('type', 'guarantee');
    }
    public function scopeLiquida($query) {
        return $query->where('type', 'liquidations');
    }
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
        'type','user_id','reason','content','equi_id','image',
    ];
    public function action_user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function action_equipment(){
        return $this->belongsTo('App\Models\Equipment','equi_id','id');
    }
}
