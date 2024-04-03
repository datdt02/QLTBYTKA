<?php
namespace App\Models;
namespace App\Policies;
use App\Models\User;
use App\Models\Device;
use Illuminate\Auth\Access\HandlesAuthorization;
 
class DevicePolicy
{
  use HandlesAuthorization;
 
  public function view(User $user, Device $device)
  {
    // visitors cannot view unpublished items
      if ($user === null) {
          return false;
      }

      // admin overrides published status
      if ($user->can('device.read')) {
          return true;
      }

      // authors can view their own unpublished posts
      return $user->id == $device->author_id;
  }
 
  public function create(User $user)
  {
    if($user->can('device.create')) {
        return true;
      }
  }
 
  public function update(User $user, Device $device)
  {
    if ($user->can('device.update')) {
        return true;
    }
    return $user->id == $device->author_id;
  }
 
  public function delete(User $user, Device $device)
  {
    if ($user->can('device.delete')) {
        return true;
    }
    return $user->id == $device->author_id;
  }
}