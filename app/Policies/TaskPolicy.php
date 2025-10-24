<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('show-any-task');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('show-any-task') || ($user->hasPermissionTo('show-task') && $task->user_id == $user->id);
    }

    /**
     * Determine whether the user can list all tasks.
     */
    public function list(User $user): bool
    {
        return $user->hasPermissionTo('list-task');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-task');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('update-any-task') || ($user->hasPermissionTo('update-task') && $user->id == $task->user_id);
    }
}
