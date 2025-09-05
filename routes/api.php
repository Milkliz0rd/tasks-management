<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/pending-low-priority', 

    /** exercices section */

    [TaskController::class, 'pendingLow']); // route to get tasks by pending status, low priority & sorted by latest
    //I put this route here beacause you need to put specific routes before dynamic ones

    Route::get('/{id}', [TaskController::class, 'showById']); // route to get task by id, use laravel method to found controller with class

    /** end */

    Route::get('/', [TaskController::class, 'create']);
    Route::delete('/{task}', [TaskController::class, 'destroy']);
});
