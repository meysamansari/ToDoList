<?php

namespace App\Http\Controllers;


use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;

class TaskController extends Controller
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index()
    {
        $tasks = $this->taskRepository->index(auth()->id());
        return TaskResource::collection($tasks);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task = $this->taskRepository->show($task);
        return new TaskResource($task);
    }

    public function store(TaskStoreRequest $request)
    {
        $this->authorize('create', Task::class);

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        if (!isset($data['status'])) {
            $data['status'] = 'todo';
        }
        $task = $this->taskRepository->create($data);
        return new TaskResource($task);
    }

    public function update(TaskUpdateRequest $request,Task $task)
    {
        $this->authorize('update', $task);
        $validatedData = $request->validated();
        $updatedTask = $this->taskRepository->update($task, $validatedData);
        return new TaskResource($updatedTask);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task = $this->taskRepository->show($task);
        $this->taskRepository->delete($task);
        return response()->json(['message' => 'task deleted successfully']);
    }
}
