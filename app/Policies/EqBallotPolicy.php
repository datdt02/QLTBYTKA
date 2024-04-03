<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\EquipmentBallot;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class EqBallotPolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, EquipmentBallot $eqballot)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('eqballot.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $eqballot->user_id;
  }
 
  public function create(User $user)
  {
    if($user->can('eqballot.create')) {
        return true;
      }
  }
 
  public function update(User $user, EquipmentBallot $eqballot)
  {
    if ($user->can('eqballot.update')) {
        return true;
    }
    return $user->id == $eqballot->user_id;
  }
 
  public function delete(User $user, EquipmentBallot $eqballot)
  {
    if ($user->can('eqballot.delete')) {
        return true;
    }
    return $user->id == $eqballot->user_id;
  }
}