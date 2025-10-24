<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TaskDependenciesNotCompletedException;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Auth;
use Log;

class TaskController extends Controller
{
    public function create(CreateTaskRequest $request, TaskService $service)
    {
        $task = $service->create($request);
        return new JsonResponse([
            'success' => true,
            'message' => 'task created successfully',
            'data' => new TaskResource($task)
        ]);
    }

    public function index(TaskService $service)
    {
        $tasks = Auth::user()->can('list-tasks')
            ? $tasks = $service->all()
            : $tasks = $service->all(Auth::id());

        return new JsonResponse([
            'success' => true,
            'data' => TaskResource::collection($tasks)
        ]);
    }

    public function show(Task $task)
    {
        return new JsonResponse([
            'success' => true,
            'data' => new TaskResource($task)
        ]);
    }

    public function update(UpdateTaskRequest $request, TaskService $service, Task $task)
    {
        $data = collect($request->all());
        $message = 'task updated successfully';
        $status = Response::HTTP_OK;

        if (Auth::user()->can('update-task')) {
            if ($data->keys()->diff(['status'])->isNotEmpty()) {
                $message = 'you cannot update any fields other the status of your own tasks';
                $status = Response::HTTP_UNPROCESSABLE_ENTITY;
            }
        }

        try {
            $task = $service->update(collect($data), $task);
            return new JsonResponse([
                'success' => true,
                'message' => $message,
                'data' => new TaskResource($task)
            ], $status);
        } catch (TaskDependenciesNotCompletedException $th) {
            return new JsonResponse([
                'success' => false,
                'message' => $th->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
