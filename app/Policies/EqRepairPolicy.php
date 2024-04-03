<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\ScheduleRepair;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class EqRepairPolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, ScheduleRepair $eqrepair)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('eqrepair.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $eqrepair->user_id;
  }
 
  public function create(User $user)
  {
    if($user->can('eqrepair.create')) {
        return true;
      }
  }
 
  public function update(User $user, ScheduleRepair $eqrepair)
  {
    if ($user->can('eqrepair.update')) {
        return true;
    }
    return $user->id == $eqrepair->user_id;
  }
 
  public function delete(User $user, ScheduleRepair $eqrepair)
  {
    if ($user->can('eqrepair.delete')) {
        return true;
    }
    return $user->id == $eqrepair->user_id;
  }
  public function approved(ScheduleRepair $eqrepair)
  {
    if ($user->can('eqrepair.approved')) {
        return true;
    }
  }
}