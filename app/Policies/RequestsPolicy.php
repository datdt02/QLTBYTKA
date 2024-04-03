<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Requests;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class RequestsPolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Requests $requests)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('requests.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $requests->user_id;
  }
 
  public function create(User $user)
  {
    if($user->can('requests.create')) {
        return true;
      }
  }
 
  public function update(User $user, Requests $requests)
  {
    if ($user->can('requests.update')) {
        return true;
    }
  }
 
  public function delete(User $user, Requests $requests)
  {
    if ($user->can('requests.delete')) {
        return true;
    }
    return $user->id == $requests->user_id;
  }
}