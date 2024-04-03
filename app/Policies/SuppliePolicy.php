<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Supplie;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class SuppliePolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Supplie $supplie)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('supplie.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $supplie->author_id;
  }
 
  public function create(User $user)
  {
    if($user->can('supplie.create')) {
        return true;
      }
  }
 
  public function update(User $user, Supplie $supplie)
  {
    if ($user->can('supplie.update')) {
        return true;
    }
    return $user->id == $supplie->author_id;
  }
 
  public function delete(User $user, Supplie $supplie)
  {
    if ($user->can('supplie.delete')) {
        return true;
    }
    return $user->id == $supplie->author_id;
  }
}