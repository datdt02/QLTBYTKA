<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Equipment extends Model
{
    use SluggableScopeHelpers;
    use Sluggable;
    use LogsActivity;

    protected $table = "equipments";

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
        'title',
        'slug',
        'code',
        'model',
        "hash_code",
        'warehouse',
        'year_manufacture',
        'serial',
        'status',
        'risk',
        'amount',
        'manufacturer',
        'origin',
        'maintenance_id',
        'provider_id',
        'repair_id',
        'user_id',
        'cate_id',
        'devices_id',
        'unit_id',
        'department_id',
        'image',
        'last_inspection',
        'next_inspection',
        'last_maintenance',
        'next_maintenance',
        'specificat',
        'first_value',
        'present_value',
        'process',
        'year_use',
        'officer_charge_id',
        'officers_use_id',
        'first_information',
        'import_price',
        'bid_project_id',
        'warranty_date',
        'configurat',
        'depreciat',
        'note',
        'officer_department_charge_id',
        'officers_training_id',
        'supplie_id',
        'regular_inspection',
        'regular_maintenance',
        'date_failure',
        'reason',
        'date_delivery',
        'liquidation_date',
        'date_person_id',
        'update_day',
        'last_radiation_inspection',
        'next_radiation_inspection',
        "periodic_radiation_inspection",
        "jv_contract_termination_date",
        "next_external_quality_assessment",
        "last_external_quality_assessment",
        "period_of_external_quality_assessment",
        "period_of_clinic_environment_inspection",
        "last_clinic_environment_inspection",
        "next_clinic_environment_inspection",
        "period_of_license_renewal_of_radiation_work",
        "last_license_renewal_of_radiation_work",
        "next_license_renewal_of_radiation_work",
    ];

    protected static $logAttributes = ['title',
                                       'status',
                                       'type',
                                       'code',
                                       'department_id',
                                       'date_failure',
                                       'reason',
                                       'liquidation_date'];


    /*public function scopeDevice($query) {
        return $query->where('type', 'devices');
    }
*/

    public function equipment_provider(): BelongsTo
    {
        return $this->belongsTo('App\Models\Provider', 'provider_id', 'id');
    }

    public function equipment_maintenance(): BelongsTo
    {
        return $this->belongsTo('App\Models\Provider', 'maintenance_id', 'id');
    }

    public function equipment_repair(): BelongsTo
    {
        return $this->belongsTo('App\Models\Provider', 'repair_id', 'id');
    }

    public function equipment_user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function equipment_unit(): BelongsTo
    {
        return $this->belongsTo('App\Models\Unit', 'unit_id', 'id');
    }

    public function equipment_department(): BelongsTo
    {
        return $this->belongsTo('App\Models\Department', 'department_id', 'id')->withDefault(
            [
                "title" => "",

            ]
        );
    }

    public function equipment_cates(): BelongsTo
    {
        return $this->belongsTo('App\Models\Cates', 'cate_id', 'id');
    }


    public function equipment_user_charge(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'officer_charge_id', 'id');
    }

    public function equipment_user_use(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User', 'equipment_user_use', 'equipment_id', 'user_id');
    }

    public function equipment_user_department_charge(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'officer_department_charge_id', 'id');
    }

    public function equipment_user_training(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User', 'equipment_user_training', 'equipment_id', 'user_id');
    }

    public function equipment_supplie(): BelongsTo
    {
        return $this->belongsTo('App\Models\Supplie', 'supplie_id', 'id');
    }

    public function equipment_device(): BelongsTo
    {
        return $this->belongsTo('App\Models\Device', 'devices_id', 'id');
    }

    public function equipment_transfer(): HasMany
    {
        return $this->hasMany('App\Models\Transfer', 'equipment_id', 'id')->latest();
    }

    public function device_supplies(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Eqsupplie', 'supplies_devices', 'device_id', 'supplie_id')->withPivot('amount', 'date_delivery', 'note', 'user_id', 'created_at');
    }

    public function radiation_inspections(): HasMany
    {
        return $this->hasMany(RadiationInspection::class, "equipment_id", "id");
    }

    public function external_quality_assessments(): HasMany
    {
        return $this->hasMany(ExternalQualityAssessment::class, "equipment_id", "id");
    }

    public function clinic_environment_inspections(): HasMany
    {
        return $this->hasMany(ClinicEnvironmentInspection::class, "equipment_id", "id");
    }

    public function license_renewal_of_radiation_works(): HasMany
    {
        return $this->hasMany(LicenseRenewalOfRadiationWork::class, "equipment_id", "id");
    }

    /**
     * Get all maintenances of equipment
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany('App\Models\Maintenance', 'equipment_id', 'id')->latest();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo('App\Models\Project', 'bid_project_id', 'id');
    }

    /**
     * Get all of the attachments for the equipment.
     */
    public function attachments(): MorphToMany
    {
        return $this->morphToMany('App\Models\Media', 'mediable')->wherePivot('type', 'attach')->withPivot('type');
    }

    public function hand_over(): MorphToMany
    {
        return $this->morphToMany('App\Models\Media', 'mediable')->wherePivot('type', 'hand_over')->withPivot('type');
    }

    public function was_broken(): MorphToMany
    {
        return $this->morphToMany('App\Models\Media', 'mediable')->wherePivot('type', 'was_broken')->withPivot('type');
    }

    public function repairs(): MorphToMany
    {
        return $this->morphToMany('App\Models\Media', 'mediable')->wherePivot('type', 'repair')->withPivot('type');
    }

    public function schedule_repairs(): HasMany
    {
        return $this->hasMany('App\Models\ScheduleRepair', 'equipment_id', 'id')->latest();
    }

    public function repairHistory(): HasMany
    {
        return $this->hasMany('App\Models\ScheduleRepair', 'equipment_id', 'id');
    }

    public function liquidations(): HasMany
    {
        return $this->hasMany('App\Models\Liquidation', 'equipment_id', 'id');
    }

    public function guarantees(): HasMany
    {
        return $this->hasMany('App\Models\Guarantee', 'equipment_id', 'id')->latest();
    }

    public function accres(): HasMany
    {
        return $this->hasMany('App\Models\Accre', 'equipment_id', 'id')->latest();
    }

    public function ballots(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\EquipmentBallot', 'ballots_equipments', 'equipment_id', 'ballot_id')->withPivot('amount', 'unit_price');
    }

    public function remaining_amount(): int
    {
        return intval($this->amount) - intval($this->liquidations->where('status', 'waiting')->where('amount', '!=', null)->sum('amount'));
    }

    public function inventories(): HasMany
    {
        return $this->hasMany('App\Models\Inventory', 'equipment_id', 'id');
    }

    public function history_inventories(): HasMany
    {
        return $this->hasMany('App\Models\HistoryInventories', 'equipment_id', 'id');
    }

    public function equipment_img(): BelongsTo
    {
        return $this->belongsTo('App\Models\Media', 'image', 'id');
    }

    //local query scope
    //more details https://laravel.com/docs/9.x/eloquent#local-scopes
    public function scopeSupplie($query)
    {
        return $query->where('type', 'supplies');
    }

    public function scopeTitle($query, $keyword)
    {
        if ($keyword != '')
            return $query->where('title', 'like', '%' . $keyword . '%');
        return $query;
    }

    public function scopeSerial($query, $keyword)
    {
        if ($keyword != '')
            return $query->where('serial', 'like', '%' . $keyword . '%');
        return $query;
    }

    public function scopeCode($query, $keyword)
    {
        if ($keyword != '')
            return $query->where('code', 'like', '%' . $keyword . '%');
        return $query;
    }

    public function scopeModel($query, $keyword)
    {
        if ($keyword != '') return $query->where('model', 'like', '%' . $keyword . '%');
        return $query;
    }

    public function scopeManufacturer($query, $keyword)
    {
        if ($keyword != '')
            return $query->where('manufacturer', 'like', '%' . $keyword . '%');
        return $query;
    }

    public function scopeOrigin($query, $keyword)
    {
        if ($keyword != '')
            return $query->where('origin', 'like', '%' . $keyword . '%');
        return $query;
    }

    public function scopeYearManufacture($query, $keyword)
    {
        if ($keyword != '')
            return $query->where('year_manufacture', 'like', '%' . $keyword . '%');
        return $query;
    }

    public function scopeRisk($query, $risk)
    {
        if ($risk != '')
            return $query->where('risk', $risk);
        return $query;
    }

    public function scopeYearUse($query, $keyword)
    {
        if ($keyword != '')
            return $query->where('year_use', 'like', '%' . $keyword . '%');
        return $query;
    }

    public function scopeAccrediationDate($query, $date)
    {
        if ($date != '') {
            $endOfMonth = Carbon::createFromFormat('Y-m-d', $date)->endOfMonth()->format('Y-m-d');
            $startOfMonth = Carbon::createFromFormat('Y-m-d', $date)->startOfMonth()->format('Y-m-d');
            //return $query->whereRaw('DATE_ADD(equipments.last_inspection, INTERVAL equipments.regular_inspection MONTH) BETWEEN "' . $startOfMonth . '" AND "' . $endOfMonth . '"');
            return $query->whereBetween('next_inspection', [$startOfMonth, $endOfMonth]);
        }
        return $query;
    }

    public function scopeWarrantyDate($query, $startDate, $endDate)
    {
        if ($startDate != '' && $endDate != '') {
            return $query->whereBetween('warranty_date', [$startDate, $endDate]);
        }
        return $query;
    }

    public function scopeStatus($query, $status)
    {
        if ($status != '')
            return $query->where('status', 'like', '%' . $status . '%');
        return $query;
    }

    public function scopeCate($query, $cate_id)
    {
        if ($cate_id != '')
            return $query->whereRelation('equipment_cates', 'id', $cate_id);
        return $query;
    }

    public function scopeDevice($query, $device_id)
    {
        if ($device_id != '')
            return $query->whereRelation('equipment_device', 'id', $device_id);
        return $query;
    }

    public function scopeDepartment($query, $department_id)
    {
        if ($department_id != '')
            return $query->whereRelation('equipment_department', 'id', $department_id);
        return $query;
    }

    public function scopeProject($query, $bid_project_id)
    {
        if ($bid_project_id != '')
            return $query->whereRelation('project', 'id', $bid_project_id);
        return $query;
    }

    public function scopeRadiationInspectionTime($query, $type_of_inspection, $time)
    {
        if ($time !== "") {
            $time = Carbon::createFromFormat("Y-m", $time);
            $start = $time->startOfMonth()->format("Y-m-d");
            $end = $time->endOfMonth()->format("Y-m-d");
            switch ($type_of_inspection) {
                case "":
                    return $query->whereBetween("last_radiation_inspection", [$start, $end]);
                case "next":
                    return $query->whereBetween("next_radiation_inspection", [$start, $end]);
            }
        }
        return $query;
    }

    public function scopePeriodicRadiationInspectionTime($query, $periodic_radiation_inspection)
    {
        //dd($periodic_radiation_inspection);
        if ($periodic_radiation_inspection != "") {
            return $query->where("periodic_radiation_inspection", $periodic_radiation_inspection);
        }
        return $query;
    }

    public function scopeOrderEquipmentsByTypeOfInspection($query, $type_of_inspection)
    {
        // order by type of inspection: last_inspection / next_inspection
        return $query->orderBy(($type_of_inspection == "" ? "last" : "next") . "_radiation_inspection", "desc");
    }

    public function scopeInspectionStatus($query)
    {
        // status = active, corrected, was_broken, not_handed
        //not in inactive, liquidated
        return $query->whereNotIn('status', ['inactive', 'liquidated']);
    }

    public function scopeJvContract($query, $date = "")
    {
        if ($date != "") {
            $date = Carbon::createFromFormat("Y-m-d", $date);
            $start = $date->startOfMonth()->format("Y-m-d");
            $end = $date->endOfMonth()->format("Y-m-d");
            return $query->whereBetween("jv_contract_termination_date", [$start, $end]);
        }
        return $query;
    }


    public function scopeJvContractExpireNextMonth($query)
    {
        $now = Carbon::now()->addMonth()->format("Y-m-d");
        return $this->scopeJvContract($query, $now);
    }

    public function scopeExternalQualityAssessmentTime($query, $type_of_inspection, $time)
    {
        if ($time !== "") {
            $time = Carbon::createFromFormat("Y-m", $time);
            $start = $time->startOfMonth()->format("Y-m-d");
            $end = $time->endOfMonth()->format("Y-m-d");
            switch ($type_of_inspection) {
                case "":
                    return $query->whereBetween("last_external_quality_assessment", [$start, $end]);
                case "next":
                    return $query->whereBetween("next_external_quality_assessment", [$start, $end]);
            }
        }
        return $query;
    }

    public function scopePeriodExternalQualityAssessment($query, $period_of_external_quality_assessment)
    {
        if ($period_of_external_quality_assessment != "") {
            return $query->where("period_of_external_quality_assessment", $period_of_external_quality_assessment);
        }
        return $query;
    }


    public function scopeOrderEquipmentsByTypeOfExternalQualityAssessment($query, $type_of_inspection)
    {
        // order by type of inspection: last_inspection / next_inspection
        return $query->orderBy(($type_of_inspection == "" ? "last" : "next") . "_external_quality_assessment", "desc");
    }


    public function scopeClinicEnvironmentInspectionTime($query, $type_of_inspection, $time)
    {
        if ($time !== "") {
            $time = Carbon::createFromFormat("Y-m", $time);
            $start = $time->startOfMonth()->format("Y-m-d");
            $end = $time->endOfMonth()->format("Y-m-d");
            switch ($type_of_inspection) {
                case "":
                    return $query->whereBetween("last_clinic_environment_inspection", [$start, $end]);
                case "next":
                    return $query->whereBetween("next_clinic_environment_inspection", [$start, $end]);
            }
        }
        return $query;
    }

    public function scopePeriodClinicEnvironmentInspection($query, $period_of_clinic_environment_inspection)
    {
        if ($period_of_clinic_environment_inspection != "") {
            return $query->where("period_of_clinic_environment_inspection", $period_of_clinic_environment_inspection);
        }
        return $query;
    }

    public function scopeOrderEquipmentsByTypeOfClinicEnvironmentInspection($query, $type_of_inspection)
    {
        // order by type of inspection: last_inspection / next_inspection
        return $query->orderBy(($type_of_inspection == "" ? "last" : "next") . "_clinic_environment_inspection", "desc");
    }


    public function scopeLicenseRenewalOfRadiationWorkTime($query, $type_of_inspection, $time)
    {
        if ($time !== "") {
            $time = Carbon::createFromFormat("Y-m", $time);
            $start = $time->startOfMonth()->format("Y-m-d");
            $end = $time->endOfMonth()->format("Y-m-d");
            switch ($type_of_inspection) {
                case "":
                    return $query->whereBetween("last_license_renewal_of_radiation_work", [$start, $end]);
                case "next":
                    return $query->whereBetween("next_license_renewal_of_radiation_work", [$start, $end]);
            }
        }
        return $query;
    }

    public function scopePeriodLicenseRenewalOfRadiationWork($query, $period_of_license_renewal_of_radiation_work)
    {
        if ($period_of_license_renewal_of_radiation_work != "") {
            return $query->where("period_of_license_renewal_of_radiation_work", $period_of_license_renewal_of_radiation_work);
        }
        return $query;
    }

    public function scopeOrderEquipmentsByTypeOfLicenseRenewalOfRadiationWork($query, $type_of_inspection)
    {
        // order by type of inspection: last_inspection / next_inspection
        return $query->orderBy(($type_of_inspection == "" ? "last" : "next") . "_license_renewal_of_radiation_work", "desc");
    }


    public function scopeMaintenanceTime($query, $type_of_inspection, $time)
    {
        if ($time !== "") {
            $time = Carbon::createFromFormat("Y-m", $time);
            $start = $time->startOfMonth()->format("Y-m-d");
            $end = $time->endOfMonth()->format("Y-m-d");
            switch ($type_of_inspection) {
                case "":
                    return $query->whereBetween("last_maintenance", [$start, $end]);
                case "next":
                    return $query->whereBetween("next_maintenance", [$start, $end]);
            }
        }
        return $query;
    }

    public function scopeRegularMaintenance($query, $regular_maintenance)
    {
        if ($regular_maintenance != "") {
            return $query->where("regular_maintenance", $regular_maintenance);
        }
        return $query;
    }

    public function scopeOrderEquipmentsByTypeOfMaintenance($query, $type_of_inspection)
    {
        // order by type of inspection: last_inspection / next_inspection
        return $query->orderBy(($type_of_inspection == "" ? "last" : "next") . "_maintenance", "desc");
    }


    public function scopeHashCode($query, $keyword)
    {
        if ($keyword != "" && $keyword != null) {
            return $query->where("hash_code", "like", "%" . $keyword . "%");
        } else {
            return $query;
        }
    }


}
