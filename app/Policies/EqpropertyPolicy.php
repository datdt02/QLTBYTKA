<?php

namespace App\Policies;

use App\Models\Eqproperty;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EqpropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Eqproperty  $eqproperty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Eqproperty $eqproperty)
    {
        // visitors cannot view unpublished items
      if ($user === null) {
        return false;
    }

    // admin overrides published status
    if ($user->can('eqproperty.show_all')) {
        return true;
    }

    // authors can view their own unpublished posts
    return $user->id == $eqproperty->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if($user->can('eqproperty.create')) {
            return true;
          }
          else return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Eqproperty  $eqproperty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Eqproperty $eqproperty)
    {
        //
        if ($user->can('eqproperty.update')) {
            return true;
        }
        return $user->id == $eqproperty->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Eqproperty  $eqproperty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Eqproperty $eqproperty)
    {
        //
        if ($user->can('eqproperty.delete')) {
            return true;
        }
        return $user->id == $eqproperty->user_id;
    }

}
