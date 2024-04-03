<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Eqsupplie;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class EqsuppliePolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Eqsupplie $eqsupplie)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('eqsupplie.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $eqsupplie->user_id;
  }
 
  public function create(User $user)
  {
    if($user->can('eqsupplie.create_input')) {
        return true;
      }
  }
 
  public function update(User $user, Eqsupplie $eqsupplie)
  {
    if ($user->can('eqsupplie.update')) {
        return true;
    }
    return $user->id == $eqsupplie->user_id;
  }
 
  public function delete(User $user, Eqsupplie $eqsupplie)
  {
    if ($user->can('eqsupplie.delete')) {
        return true;
    }
    return $user->id == $eqsupplie->user_id;
  }
}