<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function index(int $user_id): Collection;
    public function show(Task $task);
    public function create(array $data);
    public function update(Task $task, array $data);
    public function delete(Task $task);
}

class TaskRepository implements TaskRepositoryInterface
{
    public function index(int $user_id): Collection
    {
        return Task::where('user_id', $user_id)->get();
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function create(array $data)
    {
        return Task::create( $data);
    }

    public function update(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task)
    {
        $task->delete();
    }
}
