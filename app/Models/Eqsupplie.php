<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Spatie\Activitylog\Traits\LogsActivity;
class Eqsupplie extends Model {
    use SluggableScopeHelpers;
    use Sluggable;
    use LogsActivity;
    protected $table = "equipment_supplies";
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
        'title',// tên
        'slug',
        'code',// mã thiết bị
        'model',// mã model
        'warehouse',// ngày nhập kho
        'year_manufacture',// năm sản xuất
        'serial',// mã serial
        'status', // trạng thái
        'risk',// mức độ rủi ro
        'amount',
        'manufacturer', // hãng sản xuất
        'origin', // nước sản xuất
        'maintenance_id', // mã bảo dưỡng
        'repair_id',
        'unit_id', // đơn vị tính
        'department_id', // mã khoa phòng
        'image',
        'last_inspection', // lần cuối kiểm định
        'specificat', // thông số kỹ thuật
        'first_value',
        'process',
        'year_use', // năm sử dụng
        'officer_charge_id',
        'officers_use_id',
        'first_information',
        'import_price', // giá khi nhập
        'project_id', // mã dự án
        'warranty_date', // thời gian bảo hành
        'configurat', // cấu hình thiết bị
        'depreciat', // khấu hao
        'note',// ghi chu
        'votes',
        'officer_department_charge_id',
        'officers_training_id',
        'supplie_id', // nhà cung cấp
        'author_id',
        'expiry',
        'used',
        'date_delivery'
    ];
    protected static $logAttributes = ['title','status','code'];
    public function action_repair(){
        return $this->hasMany('App\Models\Action','equi_id','id')->where('type','equipment_repair')->latest();
    }
    public function eqsupplie_user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function eqsupplie_unit(){
        return $this->belongsTo('App\Models\Unit','unit_id','id');
    }
    public function eqsupplie_supplie(){
        return $this->belongsTo('App\Models\Supplie','supplie_id','id');
    }
    public function eqsupplie_provider(){
        return $this->belongsTo('App\Models\Provider','provider_id','id');
    }
    public function supplie_devices(){
        return $this->belongsToMany('App\Models\Equipment','supplies_devices','supplie_id','device_id')->withTimestamps()->withPivot('amount','date_delivery','note','user_id','created_at');
    }
    public function compatibles(){
        return $this->hasMany('App\Models\SupplieDevice','supplie_id','id');
    }
    public function used_amount(){
        return intval($this->compatibles->where('amount', '!=', null)->sum('amount'));
    }
    public function remaining_amount(){
        return intval($this->amount) - intval($this->compatibles->where('amount', '!=', null)->sum('amount'));
    }
    public function searchKey($query,$keyword)
    {
        return $this->where(function ($query) use ($keyword) {
            $query->where('title','like','%'.$keyword.'%')
                ->orWhere('code','like','%'.$keyword.'%')
                ->orWhere('model','like','%'.$keyword.'%')
                ->orWhere('serial','like','%'.$keyword.'%');
            });
    }
    ///số lượng còn lại eqsup
     public function ballots_supplies(){
        return $this->belongsToMany('App\Models\SupplieBallot','ballots_supplies','supplie_id','ballot_id')->withPivot('amount','unit_price','department_id');
    }
    public function ballot_sup(){
        return $this->hasMany('App\Models\BallotSupplie','supplie_id','id');
    }
    public function ballot_amount(){
        return intval($this->amount) - (intval($this->compatibles->where('amount', '!=', null)->sum('amount')) + intval($this->ballot_sup->where('amount', '!=', null)->sum('amount')));
    }
    public function ballot_used_amount(){
        return intval($this->compatibles->where('amount', '!=', null)->sum('amount')) + intval($this->ballot_sup->where('amount', '!=', null)->sum('amount'));
    }
    ///

}
