<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Action;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class ActionPolicy{

    use HandlesAuthorization;
    public function view(User $user, Action $action){
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // admin overrides published status
        $actions = $action->type;
        switch ($actions) {
            case "equipment_repair":
                if($user->can('eqrepair.read')) {
                    return true;
                }
            break;
            case "periodic_maintenance":
                if($user->can('maintenance.read')) {
                    return true;
                }
            break;
            case "accreditation":
                if($user->can('eqaccre.read')) {
                    return true;
                }
            break;
            case "transfers":
                if($user->can('transfer.read')) {
                    return true;
                }
            break;
            case "liquidations":
                if($user->can('liquidation.read')) {
                    return true;
                }
            break;
            case "guarantee":
                if($user->can('guarantee.read')) {
                    return true;
                }
            break;
        }
      
        // authors can view their own unpublished posts
        return $user->id == $action->user_id;
    }
 
    public function create(User $user){

        if($user->can('eqrepair.create')) {
            return true;
        }
    }
 
    public function update(User $user, Action $action){
        $actions = $action->type;
        switch ($actions) {
            case "equipment_repair":
                if($user->can('eqrepair.update')) {
                    return true;
                }
            break;
            case "periodic_maintenance":
                if($user->can('maintenance.update')) {
                    return true;
                }
            break;
            case "accreditation":
                if($user->can('eqaccre.update')) {
                    return true;
                }
            break;
            case "transfers":
                if($user->can('transfer.update')) {
                    return true;
                }
            break;
            case "liquidations":
                if($user->can('liquidation.update')) {
                    return true;
                }
            break;
            case "guarantee":
                if($user->can('guarantee.update')) {
                    return true;
                }
            break;
        }
        return $user->id == $action->author_id;
    }
 
    public function delete(User $user, Action $action){
        $actions = $action->type;
        switch ($actions) {
            case "equipment_repair":
                if($user->can('eqrepair.delete')) {
                    return true;
                }
            break;
            case "periodic_maintenance":
                if($user->can('maintenance.delete')) {
                    return true;
                }
            break;
            case "accreditation":
                if($user->can('eqaccre.delete')) {
                    return true;
                }
            break;
            case "transfers":
                if($user->can('transfer.delete')) {
                    return true;
                }
            break;
            case "liquidations":
                if($user->can('liquidation.delete')) {
                    return true;
                }
            break;
            case "guarantee":
                if($user->can('guarantee.delete')) {
                    return true;
                }
            break;
        }
        return $user->id == $action->author_id;
    }
}