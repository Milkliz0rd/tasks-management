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

    /**
     * Show task by id
     * GET /api/tasks/{task}
     */

    public function showById($id): JsonResponse
    {
      $task = Task::find($id); //laravel method to find by id

      if(!$task){ //if the task id has not found
        return response()->json([
            'success' => false,
            'message' => 'Task not found ❌',
        ], 404); // Laravel method to spend an 404 error
      }else{ //if the task id has been found
        return response()->json([
            'success' => true,
            'message' => 'Task found ✅',
            'data' => $task,
        ]);
      }
    }

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
