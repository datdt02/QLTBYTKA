<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\SupplieBallot;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class SupplieBallotPolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, SupplieBallot $supplieballot)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('supplieballot.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $supplieballot->user_id;
  }
 
  public function create(User $user)
  {
    if($user->can('supplieballot.create')) {
        return true;
      }
  }
 
  public function update(User $user, SupplieBallot $supplieballot)
  {
    if ($user->can('supplieballot.update')) {
        return true;
    }
    return $user->id == $supplieballot->user_id;
  }
 
  public function delete(User $user, SupplieBallot $supplieballot)
  {
    if ($user->can('supplieballot.delete')) {
        return true;
    }
    return $user->id == $supplieballot->user_id;
  }
}