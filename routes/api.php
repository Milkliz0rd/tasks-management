<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/{id}', [TaskController::class, 'showById']); // route to get task by id, use laravel method to found controller with class
    Route::get('/', [TaskController::class, 'create']);
    Route::delete('/{task}', [TaskController::class, 'destroy']);
});
