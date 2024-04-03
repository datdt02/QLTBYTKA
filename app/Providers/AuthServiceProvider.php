<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Department;
use App\Policies\DepartmentPolicy;
use App\Models\Provider;
use App\Policies\ProviderPolicy;
use App\Models\Cates;
use App\Policies\Equipment_catePolicy;
use App\Models\Device;
use App\Policies\DevicePolicy;
use App\Models\Supplie;
use App\Policies\SuppliePolicy;
use App\Models\Unit;
use App\Policies\UnitPolicy;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use App\Models\Eqsupplie;
use App\Policies\EqsuppliePolicy;
use App\Models\Action;
use App\Policies\ActionPolicy;
use App\Models\Equipment;
use App\Policies\EquipmentsPolicy;
use App\Models\Eqproperty;
use App\Policies\EqpropertyPolicy;
use App\Models\ScheduleRepair;
use App\Policies\EqRepairPolicy;
use App\Models\Liquidation;
use App\Policies\LiquidationPolicy;
use App\Models\Maintenance;
use App\Policies\MaintenancePolicy;
use App\Models\Transfer;
use App\Policies\TransferPolicy;
use App\Models\Requests;
use App\Policies\RequestsPolicy;
use App\Models\EquipmentBallot;
use App\Policies\EqBallotPolicy;
use App\Models\SupplieBallot;
use App\Policies\SupplieBallotPolicy;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Department::class => DepartmentPolicy::class,
        Provider::class => ProviderPolicy::class,
        Cates::class => Equipment_catePolicy::class,
        Device::class => DevicePolicy::class,
        Supplie::class => SuppliePolicy::class,
        Unit::class => UnitPolicy::class,
        Project::class => ProjectPolicy::class,
        Eqsupplie::class => EqsuppliePolicy::class,
        Eqproperty::class => EqpropertyPolicy::class,
        Action::class => ActionPolicy::class,
        Equipment::class => EquipmentsPolicy::class,
        ScheduleRepair::class => EqRepairPolicy::class,
        Liquidation::class => LiquidationPolicy::class,
        Maintenance::class => MaintenancePolicy::class,
        Transfer::class => TransferPolicy::class,
        Requests::class => RequestsPolicy::class,
        EquipmentBallot::class => EqBallotPolicy::class,
        SupplieBallot::class => SupplieBallotPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
