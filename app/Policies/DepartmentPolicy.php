<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Department;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class DepartmentPolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Department $department)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('department.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $department->author_id;
  }
 
  public function create(User $user)
  {
    if($user->can('department.create')) {
        return true;
      }
  }
 
  public function update(User $user, Department $department)
  {
    if ($user->can('department.update')) {
        return true;
    }
    return $user->id == $department->author_id;
  }
 
  public function delete(User $user, Department $department)
  {
    if ($user->can('department.delete')) {
        return true;
    }
    return $user->id == $department->author_id;
  }
}