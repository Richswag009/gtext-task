<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //
    use HttpResponses;

    // public function __construct()
    // {
    //     $this->middleware("auth:api",['except'=>['login','register']]);
    // }
    /**
     * fetch al tasks.
     */
    public function index(Request $request)
    {

        $user = auth()->user();

        $query = Task::query()->where('user_id', $user->id); 

        if ($request->has('priority')) {
            $query->where('priority', $request->priority); 
        }

        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('due_date', [$request->start_date, $request->end_date]);
        }
        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);
        }

        $tasks = $query->get();

        return $this->success($tasks, "Tasks fetched successfully");
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = request()->user();

        try {
            //code...
            $validated = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'required|date',
                'priority' => 'required|string',
            ]);

            if ($validated->fails()) {
                return $this->invalidRequest($validated->errors());
            }

            $task = $user->tasks()->create($request->all());
            return $this->success($task, 'task created successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage() ?? 'Internal server error');
        }
    }

    /**
     * Show a single resource the specified resource.
     */
    public function show(Task $task)
    {
        $user = auth()->user();

        if (!$task || $task->user_id !== $user->id) {
            return $this->notFound("Task not found for this user");
        }

        return $this->success($task,"Task fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {

        $user = auth()->user();

        try {
            if ($task->user_id !== $user->id) {
                return $this->forbidden("cant update task for this user for this user");
            }

            $validated = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'required|date',
                'priority' => 'required|string',
            ]);

            if ($validated->fails()) {
                return $this->invalidRequest($validated->errors());
            }



            $task->update($request->all());

            return $this->success($task, "task updated successfully");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->error($th->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {


        $user = auth()->user();

        if ($task->user_id !== $user->id) {
            return $this->forbidden("cant delete task for this user for this user");
        }

        $task->delete();
        return $this->success([], "task deleted successfully");
    }
}
