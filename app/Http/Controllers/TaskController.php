<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $tasks = Task::all();
        $tasks = $tasks->sortBy('priority');

        return view('tasks.index', ['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $maxPriority = Task::max('priority');

        $newPriority = $maxPriority + 1;

        $task = Task::create(['name' => $request->name, 'priority' => $newPriority, 'completed' => 0]);

        return response(array('success' => true, 'redirect' => route('tasks.index')), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskRequest $request, Task $task): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $task->update(['name' => $request->name]);

        return response(array('success' => true, 'task' => $task, 'redirect' => route('tasks.index')), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $task->delete();

        return response(array('success' => true, 'redirect' => route('tasks.index')), 200)->header('Content-Type', 'application/json');
    }

    public function toggleTaskCompleted(Request $request, Task $task): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $completed = 0;
        if (isset($request->completed) && $request->completed === 'on') {
            $completed = 1;
        }

        $task->update(['completed' => $completed]);

        return response(array('success' => true, 'msg' => $task), 200)->header('Content-Type', 'application/json');
    }

    public function updateTaskPriorities(Request $request)
    {
        $items = $request->input('items');

        foreach($items as $item) {
            Task::where('id', $item['task_id'])->first()->update(['priority' => $item['priority']]);
        }

        return response(array('success' => true, 'redirect' => route('tasks.index')), 200)->header('Content-Type', 'application/json');
    }
}
