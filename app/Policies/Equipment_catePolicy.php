<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Cates;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class Equipment_catePolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Cates $equipment_cate)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('equipment_cate.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $equipment_cate->author_id;
  }
 
  public function create(User $user)
  {
    if($user->can('equipment_cate.create')) {
        return true;
      }
  }
 
  public function update(User $user, Cates $equipment_cate)
  {
    if ($user->can('equipment_cate.update')) {
        return true;
    }
    return $user->id == $equipment_cate->author_id;
  }
 
  public function delete(User $user, Cates $equipment_cate)
  {
    if ($user->can('equipment_cate.delete')) {
        return true;
    }
    return $user->id == $equipment_cate->author_id;
  }
}