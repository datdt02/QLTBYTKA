<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class ProjectPolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Project $project)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('project.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $project->author_id;
  }
 
  public function create(User $user)
  {
    if($user->can('project.create')) {
        return true;
      }
  }
 
  public function update(User $user, Project $project)
  {
    if ($user->can('project.update')) {
        return true;
    }
    return $user->id == $project->author_id;
  }
 
  public function delete(User $user, Project $project)
  {
    if ($user->can('project.delete')) {
        return true;
    }
    return $user->id == $project->author_id;
  }
}