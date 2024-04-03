<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements MustVerifyEmail{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use LogsActivity;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'displayname',
        'name',
        'email',
        'password',
        'email_verified_at',
        'image',
        'address',
        'phone',
        'gender',
        'birthday',
        'is_disabled',
        'department_id'
    ];

    protected static $logAttributes = ['name'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

   /*public function sendEmailVerificationNotification()
    {
        $this->notify(new VerificationMail);
    }*/
    /**
     * Get the metas of user
     */
    public function user_metas(){
        return $this->hasMany('App\Models\UserMeta', 'user_id', 'id');
    }

    public function user_transfer(){
        return $this->hasMany('App\Models\User','user_id','id');
    }

    public function user_equipment(){
        return $this->hasMany('App\Models\Equipment','user_id', 'id');
    }

    public function user_eqsupplie(){
        return $this->hasMany('App\Models\Eqsupplie','user_id', 'id');
    }

    public function user_action(){
        return $this->hasMany('App\Models\Action','user_id','id');
    }

    public function users_department(){
        return $this->hasOne('App\Models\Department','nursing_id','id');
    }

    // public function departments(){
    //     return $this->hasOne('App\Models\Department','user_id','id');
    // }

    /**
     * Khoa phòng ban phụ trách (phụ trách nhiều khoa phòng)
     *
     * @return BelongsToMany
     */
    public function departments(){
        return $this->belongsToMany(Department::class,'department_user','user_id', 'department_id');
    }

    public function user_equipment_charge(){
        return $this->hasOne('App\Models\Equipment','officer_charge_id','id');
    }

    public function user_equipment_use(){
        return $this->belongsToMany('App\Models\Equipment','equipment_user_use');
    }

    public function user_equipment_department_charge(){
        return $this->hasOne('App\Models\Equipment','officer_department_charge_id','id');
    }

    public function user_equipment_training(){
        return $this->belongsToMany('App\Models\Equipment','equipment_user_training');
    }

    public function user_department(){
        return $this->belongsTo('App\Models\Department','department_id','id');
    }

    /**
     * Get all maintenance_actions of user maintenance
     */
    public function actions(){
        return $this->hasMany('App\Models\MaintenanceAction', 'author_id', 'id');
    }

    public function ballots(){
        return $this->hasMany('App\Models\EquipmentBallot','user_id', 'id');
    }

    public function supplieBallots(){
        return $this->hasMany('App\Models\SupplieBallot','user_id', 'id');
    }

    /**
     * Get all media has upload by user
     */
    public function medias(){
        return $this->hasMany('App\Models\Media', 'user_id', 'id');
    }
    public function delete_user() //delete records in department_user table
    {
        # code...
        Department_User::where('user_id', $this->id)->delete();
        $this->delete();
    }


}
