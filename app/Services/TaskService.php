<?php

namespace App\Services;

use App\Exceptions\TaskDependenciesNotCompletedException;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use DB;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskService
{
    public function create($request)
    {
        $task = Task::create($request->only('title', 'description', 'due_date', 'parent_id', 'user_id'));
        return $task;
    }

    public function all($user_id = null)
    {
        $filters = [
            AllowedFilter::exact('status'),
            AllowedFilter::scope('due_date')
        ];
        $query = Task::query();

        if ($user_id)
            $query->where('user_id', $user_id);
        else
            $filters[] = AllowedFilter::exact('user_id');

        return QueryBuilder::for($query)
            ->allowedFilters($filters)
            ->allowedSorts([
                'due_date'
            ])
            ->get();
    }

    public function get($task_id)
    {
        return Task::where('id', $task_id)->with('tasks')->first();
    }

    public function update(Collection $data, Task $task)
    {
        return DB::transaction(function () use ($task, $data) {
            $task->update($data->only(['title', 'description', 'due_date', 'parent_id', 'user_id'])->toArray());
            if ($new_status = $data->get('status', false)) {
                logger($task->tasks->every(fn($task) => $task->status == 'completed'));
                if ($new_status == 'completed') {
                    if (!$task->tasks->every(fn($task) => $task->status == 'completed'))
                        throw new TaskDependenciesNotCompletedException();
                }
                $task->update(['status' => $new_status]);
            }
            return $task;
        });
    }
}
