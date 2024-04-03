<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Provider;
use Illuminate\Auth\Access\HandlesAuthorization;
class ProviderPolicy{
  use HandlesAuthorization;
    public function view(User $user, Provider $provider){
        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }
        // admin overrides published status
        $providers = $provider->type;
        switch ($providers) {
            case "providers":
                if($user->can('provider.read')) {
                    return true;
                }
            break;
            case "maintenances":
                if($user->can('maintenance.read')) {
                    return true;
                }
            break;
            case "repairs":
                if($user->can('repair.read')) {
                    return true;
                }
            break;
        }
        // authors can view their own unpublished posts
        return $user->id == $provider->author_id;
    }
    public function create(User $user){
        if($user->can('provider.create')) {
            return true;
        }
    }
    public function update(User $user, Provider $provider){
        $providers = $provider->type;
        switch ($providers) {
            case "providers":
                if($user->can('provider.update')) {
                    return true;
                }
            break;
            case "maintenances":
                if($user->can('maintenance.update')) {
                    return true;
                }
            break;
            case "repairs":
                if($user->can('repair.update')) {
                    return true;
                }
            break;
        }
        return $user->id == $provider->author_id;
    }
    public function delete(User $user, Provider $provider){
        $providers = $provider->type;
        switch ($providers) {
            case "providers":
                if($user->can('provider.delete')) {
                    return true;
                }
            break;
            case "maintenances":
                if($user->can('maintenance.delete')) {
                    return true;
                }
            break;
            case "repairs":
                if($user->can('repair.delete')) {
                    return true;
                }
            break;
        }
        return $user->id == $provider->author_id;
    }
}