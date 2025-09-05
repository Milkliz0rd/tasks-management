<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Show task list
     * GET /api/tasks
     */
    public function index(): JsonResponse
    {
        $tasks = Task::query()->get();

        return response()->json([
            'success' => true,
            'message' => 'Tasks list',
            'data' => $tasks
        ]);
    }


    /** ------ Exercices section ------ */

        /**
     * Show task with pending status, low priority & sorted
     * by creation date (most recent first)
     * GET /api/tasks/pending-low-priority
     */

    public function pendingLow(): JsonResponse
    {
        $task = Task::pending()->low()->latest('created_at')->get(); 
        //I use the scope pending & low
        // Laravel already has a method to sort by latest records
        return response()->json([
            'success' => true,
            'message' => 'Tasks found ✅',
            'data' => $task,
        ]);
    }



    /**
     * Show task by id
     * GET /api/tasks/{task}
     */

    public function showById($id): JsonResponse
    {
      $task = Task::find($id); //laravel method to find by id

      //if the task id has not found
      if(!$task){
        return response()->json([
            'success' => false,
            'message' => 'Task not found ❌',
        ], 404);
         // Laravel method to spend an 404 error

        //if the task id has been found
      }else{ 
        return response()->json([
            'success' => true,
            'message' => 'Task found ✅',
            'data' => $task,
        ]);
      }
    }

    /**  ------ end ------ */


    /**
     * Create a new task
     * POST /api/tasks
     */
    public function store(Request $request): JsonResponse
    {
        // Data Validation
        $validatedTask = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['nullable', Rule::in(Task::getStatuses())],
            'priority' => ['nullable', Rule::in(Task::getPriorities())],
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);

        try {
            // Creating the task
            $task = Task::query()->create($validatedTask);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => $task
            ], 201);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete a task
     * DELETE /api/tasks/{id}
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task successfully deleted',
            'data' => $task
        ]);
    }
}
