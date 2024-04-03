<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Equipment;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentsPolicy
{
  use HandlesAuthorization;

  public function view(User $user, Equipment $equipment)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('equipment.show_all')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $equipment->user_id;
  }

  public function create(User $user)
  {
    if($user->can('equipment.create')) {
        return true;
      }
  }

  public function update(User $user, Equipment $equipment)
  {
    if ($user->can('equipment.update')) {
        return true;
    }
    return $user->id == $equipment->user_id;
  }

  public function delete(User $user, Equipment $equipment)
  {
    if ($user->can('equipment.delete')) {
        return true;
    }
    return $user->id == $equipment->user_id;
  }
}
