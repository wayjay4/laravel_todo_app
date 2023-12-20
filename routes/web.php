<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/tasks', TaskController::class);
Route::put('/tasks/{task}/toggle_completed', [TaskController::class, 'toggleTaskCompleted'])->name('tasks.toggledCompleted');
Route::post('/tasks/update_task_priorities', [TaskController::class, 'updateTaskPriorities'])->name('tasks.updateTaskPriorities');
