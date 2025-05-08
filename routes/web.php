<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\UserController;
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

Route::redirect('/', '/login');

// login
Route::get('login', [LoginController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// signup
Route::get('signup', [SignupController::class, 'showSignup'])->middleware('guest')->name('signup');
Route::post('signup', [SignupController::class, 'signup']);

// home
Route::get('home', [UserController::class, 'showHome'])->middleware('auth')->name('home');

// task
Route::post('/tasks/add', [UserController::class, 'addTask'])->name('tasks.add')->middleware('auth');
Route::get('/tasks/get/{id}', [UserController::class, 'getTask'])->middleware('auth');
Route::get('/tasks/get', [UserController::class, 'getTasks'])->middleware('auth');
Route::post('/tasks-status/update', [UserController::class, 'updateTaskStatus'])->name('tasks_status.update');
Route::post('/tasks/update', [UserController::class, 'updateTask'])->name('tasks.update');
Route::post('/subtasks/add', [UserController::class, 'addSubTasks'])->name('subtasks.add');
Route::post('/subtasks/changeSubTasksStatus', [UserController::class, 'subTasksChangeStatus'])->name('subtasks.changestatus');
Route::post('/task/trash', [UserController::class, 'taskMoveToTrash'])->name('task.trash');
Route::get('/trash/get', [UserController::class, 'getTrash'])->middleware('auth')->name('trash.get');
