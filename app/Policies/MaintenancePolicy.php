<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Maintenance;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class MaintenancePolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Maintenance $maintenance_periodic)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('maintenance_periodic.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $maintenance_periodic->author_id;
  }
 
  public function create(User $user)
  {
    if($user->can('maintenance_periodic.create')) {
        return true;
      }
  }
 
  public function update(User $user, Maintenance $maintenance_periodic)
  {
    if ($user->can('maintenance_periodic.update')) {
        return true;
    }
    return $user->id == $maintenance_periodic->author_id;
  }
 
  public function delete(User $user, Maintenance $maintenance_periodic)
  {
    if ($user->can('maintenance_periodic.delete')) {
        return true;
    }
    return $user->id == $maintenance_periodic->author_id;
  }
}