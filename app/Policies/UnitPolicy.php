<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class UnitPolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Unit $unit)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('unit.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $unit->author_id;
  }
 
  public function create(User $user)
  {
    if($user->can('unit.create')) {
        return true;
      }
  }
 
  public function update(User $user, Unit $unit)
  {
    if ($user->can('unit.update')) {
        return true;
    }
    return $user->id == $unit->author_id;
  }
 
  public function delete(User $user, Unit $unit)
  {
    if ($user->can('unit.delete')) {
        return true;
    }
    return $user->id == $unit->author_id;
  }
}