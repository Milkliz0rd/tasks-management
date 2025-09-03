<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/', [TaskController::class, 'create']);
    Route::delete('/{task}', [TaskController::class, 'destroy']);
});
