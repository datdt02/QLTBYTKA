<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Liquidation;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class LiquidationPolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Liquidation $liquidation)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('liquidation.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $liquidation->user_id;
  }
 
  public function create(User $user)
  {
    if($user->can('liquidation.create')) {
        return true;
      }
  }
 
  public function approved(User $user, Liquidation $liquidation)
  {
    if ($user->can('liquidation.approved')) {
        return true;
    }
  }
 
  public function delete(User $user, Liquidation $liquidation)
  {
    if ($user->can('liquidation.delete')) {
        return true;
    }
    return $user->id == $liquidation->user_id;
  }
}