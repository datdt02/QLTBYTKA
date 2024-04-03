<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Transfer;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class TransferPolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Transfer $transfer)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('transfer.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $transfer->user_id ;
  }
 
  public function create(User $user)
  {
    if($user->can('transfer.create')) {
        return true;
      }
  }
 
  public function update(User $user, Transfer $transfer)
  {
    if ($user->can('transfer.update')) {
        return true;
    }
    return $user->id == $transfer->user_id ;
  }
 
  public function delete(User $user, Transfer $transfer)
  {
    if ($user->can('transfer.delete')) {
        return true;
    }
    return $user->id == $transfer->user_id ;
  }
  
}