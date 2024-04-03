<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Accre
 *
 * @property int $id
 * @property int|null $equipment_id
 * @property string|null $provider
 * @property string|null $time
 * @property string|null $note
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment|null $equipments
 * @method static \Illuminate\Database\Eloquent\Builder|Accre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Accre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Accre query()
 * @method static \Illuminate\Database\Eloquent\Builder|Accre whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Accre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Accre whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Accre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Accre whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Accre whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Accre whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Accre whereUpdatedAt($value)
 */
	class Accre extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Action
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $type
 * @property int|null $user_id
 * @property int|null $equi_id
 * @property string|null $reason
 * @property string|null $content
 * @property string|null $status
 * @property int|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment|null $action_equipment
 * @property-read \App\Models\User|null $action_user
 * @method static \Illuminate\Database\Eloquent\Builder|Action accre()
 * @method static \Illuminate\Database\Eloquent\Builder|Action eqrepair()
 * @method static \Illuminate\Database\Eloquent\Builder|Action findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Action guarantee()
 * @method static \Illuminate\Database\Eloquent\Builder|Action liquida()
 * @method static \Illuminate\Database\Eloquent\Builder|Action newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Action newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Action periodic()
 * @method static \Illuminate\Database\Eloquent\Builder|Action query()
 * @method static \Illuminate\Database\Eloquent\Builder|Action transfer()
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereEquiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereSlug(string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Action withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Action extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BallotSupplie
 *
 * @property int $id
 * @property int|null $ballot_id
 * @property int|null $supplie_id
 * @property int|null $amount
 * @property string|null $unit_price
 * @property int|null $department_id
 * @method static \Illuminate\Database\Eloquent\Builder|BallotSupplie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BallotSupplie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BallotSupplie query()
 * @method static \Illuminate\Database\Eloquent\Builder|BallotSupplie whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotSupplie whereBallotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotSupplie whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotSupplie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotSupplie whereSupplieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotSupplie whereUnitPrice($value)
 */
	class BallotSupplie extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Cates
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $code
 * @property int|null $image
 * @property int|null $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment|null $cates_equipment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Device[] $device
 * @property-read int|null $device_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Provider[] $providers
 * @property-read int|null $providers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cates findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Cates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cates query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cates whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cates whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cates whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cates whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cates whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cates whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cates withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Cates extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ClinicEnvironmentInspection
 *
 * @property int $id
 * @property int $equipment_id
 * @property string $provider
 * @property string $time
 * @property string $note
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment $equipment
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClinicEnvironmentInspection whereUpdatedAt($value)
 */
	class ClinicEnvironmentInspection extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Department
 *
 * @property int $id
 * @property string $title
 * @property string|null $code
 * @property string $slug
 * @property string|null $phone
 * @property string $contact
 * @property string $email
 * @property string $address
 * @property int|null $user_id
 * @property int|null $author_id
 * @property int|null $nursing_id
 * @property int|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $browser
 * @property string|null $browser_day
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EquipmentBallot[] $ballots
 * @property-read int|null $ballots_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $department_equipment
 * @property-read int|null $department_equipment_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transfer[] $department_transfer
 * @property-read int|null $department_transfer_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $department_user
 * @property-read int|null $department_user_count
 * @property-read \App\Models\User|null $department_users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HistoryInventories[] $history_inventories
 * @property-read int|null $history_inventories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory[] $inventories
 * @property-read int|null $inventories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplieBallot[] $supplieBallots
 * @property-read int|null $supplie_ballots_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Department findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereBrowserDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereNursingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Department extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Department_User
 *
 * @property int $id
 * @property int|null $department_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Department_User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department_User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department_User query()
 * @method static \Illuminate\Database\Eloquent\Builder|Department_User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department_User whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department_User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department_User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department_User whereUserId($value)
 */
	class Department_User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Device
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $code
 * @property int|null $image
 * @property int|null $cat_id
 * @property int|null $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $device_equipment
 * @property-read int|null $device_equipment_count
 * @property-read \App\Models\Cates|null $equipment
 * @method static \Illuminate\Database\Eloquent\Builder|Device findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device query()
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereCatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Device extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Eqsupplie
 *
 * @property int $id
 * @property string $title
 * @property string|null $model
 * @property string|null $year_manufacture
 * @property string|null $warehouse
 * @property string|null $slug
 * @property string|null $code
 * @property string|null $serial
 * @property string|null $status
 * @property string|null $risk
 * @property float|null $amount
 * @property string|null $manufacturer
 * @property string|null $origin
 * @property int|null $maintenance_id
 * @property int|null $provider_id
 * @property int|null $repair_id
 * @property int|null $user_id
 * @property int|null $unit_id
 * @property int|null $department_id
 * @property int|null $image
 * @property string|null $first_inspection
 * @property string|null $specificat
 * @property float|null $first_value
 * @property string|null $process
 * @property string|null $year_use
 * @property int|null $officer_charge_id
 * @property int|null $officer_department_charge_id
 * @property string|null $first_information
 * @property string|null $import_price
 * @property int|null $project_id
 * @property string|null $warranty_date
 * @property string|null $configurat
 * @property float|null $depreciat
 * @property string|null $note
 * @property string|null $votes
 * @property string|null $expiry
 * @property float|null $used
 * @property int|null $supplie_id
 * @property int|null $eqdevice_id
 * @property string|null $date_delivery
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Action[] $action_repair
 * @property-read int|null $action_repair_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BallotSupplie[] $ballot_sup
 * @property-read int|null $ballot_sup_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplieBallot[] $ballots_supplies
 * @property-read int|null $ballots_supplies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplieDevice[] $compatibles
 * @property-read int|null $compatibles_count
 * @property-read \App\Models\Provider|null $eqsupplie_provider
 * @property-read \App\Models\Supplie|null $eqsupplie_supplie
 * @property-read \App\Models\Unit|null $eqsupplie_unit
 * @property-read \App\Models\User|null $eqsupplie_user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $supplie_devices
 * @property-read int|null $supplie_devices_count
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereConfigurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereDateDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereDepreciat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereEqdeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereFirstInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereFirstInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereFirstValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereImportPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereMaintenanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereOfficerChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereOfficerDepartmentChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereRepairId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereRisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereSpecificat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereSupplieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereWarrantyDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereYearManufacture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie whereYearUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Eqsupplie withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Eqsupplie extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Equipment
 *
 * @property int $id
 * @property string $title
 * @property string|null $model
 * @property string|null $year_manufacture
 * @property string|null $warehouse
 * @property string $slug
 * @property string|null $code
 * @property string|null $serial
 * @property string|null $status
 * @property string|null $risk
 * @property float|null $amount
 * @property string|null $manufacturer
 * @property string|null $origin
 * @property int|null $maintenance_id
 * @property int|null $provider_id
 * @property int|null $repair_id
 * @property int|null $user_id
 * @property int|null $cate_id
 * @property int|null $devices_id
 * @property int|null $unit_id
 * @property int|null $department_id
 * @property int|null $image
 * @property string|null $last_inspection
 * @property string|null $next_inspection
 * @property string|null $last_maintenance
 * @property string|null $next_maintenance
 * @property string|null $specificat
 * @property float|null $first_value
 * @property float|null $present_value
 * @property string|null $process
 * @property string|null $year_use
 * @property int|null $officer_charge_id
 * @property int|null $officers_use_id
 * @property string|null $first_information
 * @property string|null $import_price
 * @property int|null $bid_project_id
 * @property string|null $warranty_date
 * @property string|null $configurat
 * @property float|null $depreciat
 * @property string|null $note
 * @property int|null $officer_department_charge_id
 * @property int|null $officers_training_id
 * @property int|null $supplie_id
 * @property int|null $regular_inspection
 * @property int|null $regular_maintenance
 * @property int|null $parent_id
 * @property string|null $date_failure
 * @property string|null $reason
 * @property string|null $critical_level
 * @property string|null $date_delivery
 * @property string|null $liquidation_date
 * @property int|null $date_person_id
 * @property string|null $update_day
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $funding Nguồn cung cấp cho dự án. VD: ngân sách nhà nước|Funding sources for the project. Ex: state budget
 * @property int|null $periodic_radiation_inspection Kiểm xạ định kỳ
 * @property string|null $last_radiation_inspection Kiểm xạ lần cuối
 * @property string|null $next_radiation_inspection Kiểm xạ lần kế tiếp
 * @property string|null $jv_contract_termination_date Thời điểm kết thúc hợp đồng liên doanh liên kết (joint-venture contract
 * @property int|null $period_of_external_quality_assessment Ngoại kiểm định kỳ
 * @property string|null $last_external_quality_assessment Ngoại kiểm lần cuối
 * @property string|null $next_external_quality_assessment Ngoại kiểm lần kế tiếp
 * @property int|null $period_of_clinic_environment_inspection KIểm định môi trường phòng định kỳ
 * @property string|null $last_clinic_environment_inspection KIểm định môi trường phòng lần cuối
 * @property string|null $next_clinic_environment_inspection KIểm định môi trường phòng lần kế tiếp
 * @property int|null $period_of_license_renewal_of_radiation_work Gia hạn giấy phép tiến hành CV bức xạ định kỳ
 * @property string|null $last_license_renewal_of_radiation_work Gia hạn giấy phép tiến hành CV bức xạ lần cuối
 * @property string|null $next_license_renewal_of_radiation_work Gia hạn giấy phép tiến hành CV bức xạ lần kế tiếp
 * @property string|null $hash_code
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Accre[] $accres
 * @property-read int|null $accres_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EquipmentBallot[] $ballots
 * @property-read int|null $ballots_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClinicEnvironmentInspection[] $clinic_environment_inspections
 * @property-read int|null $clinic_environment_inspections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Eqsupplie[] $device_supplies
 * @property-read int|null $device_supplies_count
 * @property-read \App\Models\Cates|null $equipment_cates
 * @property-read \App\Models\Department|null $equipment_department
 * @property-read \App\Models\Device|null $equipment_device
 * @property-read \App\Models\Media|null $equipment_img
 * @property-read \App\Models\Provider|null $equipment_maintenance
 * @property-read \App\Models\Provider|null $equipment_provider
 * @property-read \App\Models\Provider|null $equipment_repair
 * @property-read \App\Models\Supplie|null $equipment_supplie
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transfer[] $equipment_transfer
 * @property-read int|null $equipment_transfer_count
 * @property-read \App\Models\Unit|null $equipment_unit
 * @property-read \App\Models\User|null $equipment_user
 * @property-read \App\Models\User|null $equipment_user_charge
 * @property-read \App\Models\User|null $equipment_user_department_charge
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $equipment_user_training
 * @property-read int|null $equipment_user_training_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $equipment_user_use
 * @property-read int|null $equipment_user_use_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExternalQualityAssessment[] $external_quality_assessments
 * @property-read int|null $external_quality_assessments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Guarantee[] $guarantees
 * @property-read int|null $guarantees_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $hand_over
 * @property-read int|null $hand_over_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HistoryInventories[] $history_inventories
 * @property-read int|null $history_inventories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory[] $inventories
 * @property-read int|null $inventories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LicenseRenewalOfRadiationWork[] $license_renewal_of_radiation_works
 * @property-read int|null $license_renewal_of_radiation_works_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Liquidation[] $liquidations
 * @property-read int|null $liquidations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Maintenance[] $maintenances
 * @property-read int|null $maintenances_count
 * @property-read \App\Models\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RadiationInspection[] $radiation_inspections
 * @property-read int|null $radiation_inspections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ScheduleRepair[] $repairHistory
 * @property-read int|null $repair_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $repairs
 * @property-read int|null $repairs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ScheduleRepair[] $schedule_repairs
 * @property-read int|null $schedule_repairs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $was_broken
 * @property-read int|null $was_broken_count
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment accrediationDate($date)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment cate($cate_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment clinicEnvironmentInspectionTime($type_of_inspection, $time)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment code($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment department($department_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment device($device_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment externalQualityAssessmentTime($type_of_inspection, $time)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment hashCode($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment inspectionStatus()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment jvContract($date = '')
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment jvContractExpireNextMonth()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment licenseRenewalOfRadiationWorkTime($type_of_inspection, $time)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment maintenanceTime($type_of_inspection, $time)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment manufacturer($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment model($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment orderEquipmentsByTypeOfClinicEnvironmentInspection($type_of_inspection)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment orderEquipmentsByTypeOfExternalQualityAssessment($type_of_inspection)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment orderEquipmentsByTypeOfInspection($type_of_inspection)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment orderEquipmentsByTypeOfLicenseRenewalOfRadiationWork($type_of_inspection)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment orderEquipmentsByTypeOfMaintenance($type_of_inspection)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment origin($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment periodClinicEnvironmentInspection($period_of_clinic_environment_inspection)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment periodExternalQualityAssessment($period_of_external_quality_assessment)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment periodLicenseRenewalOfRadiationWork($period_of_license_renewal_of_radiation_work)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment periodicRadiationInspectionTime($periodic_radiation_inspection)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment project($bid_project_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment radiationInspectionTime($type_of_inspection, $time)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment regularMaintenance($regular_maintenance)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment risk($risk)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment serial($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment supplie()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment title($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment warrantyDate($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereBidProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereCateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereConfigurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereCriticalLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereDateDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereDateFailure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereDatePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereDepreciat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereDevicesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereFirstInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereFirstValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereFunding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereHashCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereImportPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereJvContractTerminationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereLastClinicEnvironmentInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereLastExternalQualityAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereLastInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereLastLicenseRenewalOfRadiationWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereLastMaintenance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereLastRadiationInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereLiquidationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereMaintenanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereNextClinicEnvironmentInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereNextExternalQualityAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereNextInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereNextLicenseRenewalOfRadiationWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereNextMaintenance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereNextRadiationInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereOfficerChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereOfficerDepartmentChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereOfficersTrainingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereOfficersUseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment wherePeriodOfClinicEnvironmentInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment wherePeriodOfExternalQualityAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment wherePeriodOfLicenseRenewalOfRadiationWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment wherePeriodicRadiationInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment wherePresentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereRegularInspection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereRegularMaintenance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereRepairId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereRisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereSpecificat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereSupplieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereUpdateDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereWarrantyDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereYearManufacture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereYearUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment yearManufacture($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment yearUse($keyword)
 */
	class Equipment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EquipmentBallot
 *
 * @property int $id
 * @property string|null $ballot
 * @property int|null $department_id
 * @property int|null $provider_id
 * @property int|null $user_id
 * @property string|null $date_vote
 * @property string|null $note
 * @property string|null $date_up
 * @property int|null $person_up
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Department|null $departments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $equipments
 * @property-read int|null $equipments_count
 * @property-read \App\Models\Provider|null $providers
 * @property-read \App\Models\User|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot query()
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereBallot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereDateUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereDateVote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot wherePersonUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EquipmentBallot whereUserId($value)
 */
	class EquipmentBallot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ExternalQualityAssessment
 *
 * @property int $id
 * @property int $equipment_id
 * @property string $provider
 * @property string $time
 * @property string $note
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment $equipment
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalQualityAssessment whereUpdatedAt($value)
 */
	class ExternalQualityAssessment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Guarantee
 *
 * @property int $id
 * @property int|null $equipment_id
 * @property string|null $provider
 * @property string|null $time
 * @property string|null $note
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment|null $equipments
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guarantee whereUpdatedAt($value)
 */
	class Guarantee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HistoryInventories
 *
 * @property int $id
 * @property int|null $equipment_id
 * @property string|null $note
 * @property string|null $date
 * @property int|null $user_id
 * @property int|null $times
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment|null $equipment
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories query()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories whereTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryInventories whereUserId($value)
 */
	class HistoryInventories extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Inventory
 *
 * @property int $id
 * @property int|null $equipment_id
 * @property string|null $note
 * @property string|null $date
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment|null $equipment
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory query()
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereUserId($value)
 */
	class Inventory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LicenseRenewalOfRadiationWork
 *
 * @property int $id
 * @property int $equipment_id
 * @property string $provider
 * @property string $time
 * @property string $note
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment $equipment
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork query()
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenseRenewalOfRadiationWork whereUpdatedAt($value)
 */
	class LicenseRenewalOfRadiationWork extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Liquidation
 *
 * @property int $id
 * @property int|null $equipment_id
 * @property int $amount
 * @property string|null $reason
 * @property int|null $user_id
 * @property string $status
 * @property int|null $person_up
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment|null $equipment
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation wherePersonUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Liquidation whereUserId($value)
 */
	class Liquidation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Maintenance
 *
 * @property int $id
 * @property int|null $equipment_id
 * @property string|null $provider
 * @property string|null $start_date
 * @property string $frequency
 * @property string|null $note
 * @property int|null $author_id
 * @property int|null $approve_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MaintenanceAction[] $actions
 * @property-read int|null $actions_count
 * @property-read \App\Models\User|null $approve_user
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Equipment|null $equipment
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereApproveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereUpdatedAt($value)
 */
	class Maintenance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MaintenanceAction
 *
 * @property int $id
 * @property string $code
 * @property int|null $maintenance_id
 * @property string $type C:check
 * I: inspection
 * M: maintenance
 * @property \Illuminate\Support\Carbon|null $created_date ngày thực hiện
 * @property \Illuminate\Support\Carbon|null $date_of_action
 * @property int $author_id
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Maintenance|null $maintenance
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction query()
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereCreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereDateOfAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereMaintenanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceAction whereUpdatedAt($value)
 */
	class MaintenanceAction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Media
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $alt
 * @property string|null $path
 * @property string|null $content
 * @property string $type
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Department[] $cates
 * @property-read int|null $cates_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $equipments
 * @property-read int|null $equipments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Media findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Media extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MediaCate
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaCate withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class MediaCate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MediaMediaCate
 *
 * @property int $id
 * @property int $media_id
 * @property int $cate_id
 * @method static \Illuminate\Database\Eloquent\Builder|MediaMediaCate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaMediaCate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaMediaCate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaMediaCate whereCateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaMediaCate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaMediaCate whereMediaId($value)
 */
	class MediaMediaCate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Option
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Option newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option query()
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereValue($value)
 */
	class Option extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Page
 *
 * @property int $id
 * @property string|null $content
 * @property string|null $template
 * @property int|null $post_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 */
	class Page extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int|null $image_id
 * @property string|null $excerpt
 * @property string|null $meta_key
 * @property string|null $meta_value
 * @property string $type
 * @property int|null $user_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Post findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereMetaKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereMetaValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Post extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $procurement
 * @property string|null $decision
 * @property string|null $note
 * @property string|null $status
 * @property int|null $image
 * @property string|null $fromDate
 * @property string|null $toDate
 * @property int|null $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment|null $equipment
 * @method static \Illuminate\Database\Eloquent\Builder|Project findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDecision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereFromDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProcurement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Provider
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $type
 * @property string|null $tax_code
 * @property string|null $fields_operation
 * @property string|null $note
 * @property string|null $repair
 * @property string $contact
 * @property string $email
 * @property string $address
 * @property string|null $phone
 * @property int|null $image
 * @property int|null $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EquipmentBallot[] $ballots
 * @property-read int|null $ballots_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cates[] $equipment_cates
 * @property-read int|null $equipment_cates_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Eqsupplie[] $provider_eqsupplie
 * @property-read int|null $provider_eqsupplie_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $provider_equipment
 * @property-read int|null $provider_equipment_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $provider_maintenance
 * @property-read int|null $provider_maintenance_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $provider_repair
 * @property-read int|null $provider_repair_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplieBallot[] $supplieBallots
 * @property-read int|null $supplie_ballots_count
 * @method static \Illuminate\Database\Eloquent\Builder|Provider findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider maintenance()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider provider()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider query()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider repair()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereFieldsOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereRepair($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereTaxCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Provider extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RadiationInspection
 *
 * @property int $id
 * @property int $equipment_id
 * @property string $provider
 * @property string $time
 * @property string $note
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Equipment $equipment
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection query()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiationInspection whereUpdatedAt($value)
 */
	class RadiationInspection extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Requests
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $department_id
 * @property string|null $device_name
 * @property string|null $time
 * @property string|null $serial
 * @property string|null $model
 * @property string|null $code
 * @property string|null $note
 * @property string|null $reply
 * @property int|null $person_up
 * @property string|null $time_up
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\Department|null $department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $request_media
 * @property-read int|null $request_media_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Requests newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requests newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requests query()
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereDeviceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests wherePersonUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereTimeUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requests whereUserId($value)
 */
	class Requests extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ScheduleRepair
 *
 * @property int $id
 * @property int|null $equipment_id
 * @property string|null $code
 * @property string|null $repair_date
 * @property string|null $pre_corrected
 * @property int|null $provider_id
 * @property string|null $expected_cost
 * @property string|null $acceptance
 * @property string|null $completed_repair
 * @property string|null $repaired_status
 * @property string|null $actual_costs
 * @property string|null $documents
 * @property string|null $planning_date
 * @property int|null $user_id
 * @property int|null $representative
 * @property string|null $update_date
 * @property int|null $person_up
 * @property int|null $approved
 * @property string|null $date_failure
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\Equipment|null $equipment
 * @property-read \App\Models\Provider|null $provider
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereAcceptance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereActualCosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereCompletedRepair($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereDateFailure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereExpectedCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair wherePersonUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair wherePlanningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair wherePreCorrected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereRepairDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereRepairedStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereRepresentative($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleRepair whereUserId($value)
 */
	class ScheduleRepair extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Supplie
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $code
 * @property int|null $image
 * @property int|null $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $supplie_equipment
 * @property-read int|null $supplie_equipment_count
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplie withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Supplie extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SupplieBallot
 *
 * @property int $id
 * @property string|null $ballot
 * @property int|null $department_id
 * @property int|null $provider_id
 * @property int|null $user_id
 * @property string|null $date_vote
 * @property string|null $note
 * @property string|null $date_up
 * @property int|null $person_up
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Department|null $departments
 * @property-read \App\Models\Provider|null $providers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Eqsupplie[] $supplies
 * @property-read int|null $supplies_count
 * @property-read \App\Models\User|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereBallot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereDateUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereDateVote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot wherePersonUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieBallot whereUserId($value)
 */
	class SupplieBallot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SupplieDevice
 *
 * @property int $id
 * @property int|null $supplie_id
 * @property int|null $device_id
 * @property int|null $amount
 * @property string|null $note
 * @property int|null $user_id
 * @property string|null $date_delivery
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice whereDateDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice whereSupplieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplieDevice whereUserId($value)
 */
	class SupplieDevice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transfer
 *
 * @property int $id
 * @property int|null $equipment_id
 * @property int|null $user_id
 * @property int|null $department_id
 * @property string|null $content
 * @property string|null $time_move
 * @property int|null $image
 * @property string|null $note
 * @property float|null $amount
 * @property string|null $status
 * @property int|null $approver
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Department|null $transfer_department
 * @property-read \App\Models\Equipment|null $transfer_equipment
 * @property-read \App\Models\User|null $transfer_user
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereApprover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereTimeMove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereUserId($value)
 */
	class Transfer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Unit
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int|null $image
 * @property int|null $device_id
 * @property int|null $supplie_id
 * @property int|null $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Unit[] $unit_equipment
 * @property-read int|null $unit_equipment_count
 * @method static \Illuminate\Database\Eloquent\Builder|Unit findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereSupplieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
	class Unit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $displayname
 * @property int|null $image
 * @property string|null $address
 * @property string|null $birthday
 * @property string|null $phone
 * @property int|null $department_id
 * @property string|null $gender
 * @property int|null $is_disabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MaintenanceAction[] $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EquipmentBallot[] $ballots
 * @property-read int|null $ballots_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Department[] $departments
 * @property-read int|null $departments_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $medias
 * @property-read int|null $medias_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplieBallot[] $supplieBallots
 * @property-read int|null $supplie_ballots_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Action[] $user_action
 * @property-read int|null $user_action_count
 * @property-read \App\Models\Department|null $user_department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Eqsupplie[] $user_eqsupplie
 * @property-read int|null $user_eqsupplie_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $user_equipment
 * @property-read int|null $user_equipment_count
 * @property-read \App\Models\Equipment|null $user_equipment_charge
 * @property-read \App\Models\Equipment|null $user_equipment_department_charge
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $user_equipment_training
 * @property-read int|null $user_equipment_training_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Equipment[] $user_equipment_use
 * @property-read int|null $user_equipment_use_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserMeta[] $user_metas
 * @property-read int|null $user_metas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $user_transfer
 * @property-read int|null $user_transfer_count
 * @property-read \App\Models\Department|null $users_department
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDisplayname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * App\Models\UserMeta
 *
 * @property int $id
 * @property int $user_id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta whereValue($value)
 */
	class UserMeta extends \Eloquent {}
}

