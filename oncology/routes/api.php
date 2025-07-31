<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\CeoUserController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\HealthCenterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/
/*
Route::get('welcome', [WelcomeController::class, 'welcome']);
Route::get('user/{id}', [UserController::class, 'CheckUser']);
Route::post('tasks', [TaskController::class, 'store']);
Route::get('tasks', [TaskController::class, 'index']);
Route::put('tasks/{id}', [TaskController::class, 'update']);
Route::get('tasks/{id}', [TaskController::class, 'show']);
Route::delete('tasks/{id}', [TaskController::class, 'destroy']);
*/

Route::middleware('auth:sanctum')->group(function()
{
Route::apiResource('tasks',TaskController::class);
/*
Route::prefix('profile')->group(function()
{
Route::post('',[ProfileController::class,'store']);
Route::get('profile/{id}',[ProfileController::class,'show']);
Route::put('profile/{id}', [ProfileController::class, 'update']);
});*/
route::get('user',[UserController::class, 'GetUser']);
Route::get('user/{id}/profile',[UserController::class,'getProfile']);
Route::get('user/{id}/tasks', [UserController::class, 'getUserTasks']);
Route::get('tasks/{id}/user', [TaskController::class, 'getTaskUser']);

Route::get('task/all',[TaskController::class,'getAllTasks'])->middleware('CheckUser');

Route::post('tasks/{taskId}/categories',[TaskController::class,'addCategoriesToTask']);
Route::get('tasks/{taskId}/categories',[TaskController::class,'getCategoriesToTask']);

Route::get('task/ordered',[TaskController::class,'getTaskByPriority']);

Route::post('tasks/{id}/favorite',[TaskController::class,'addToFavorites']);
Route::delete('tasks/{id}/favorite',[TaskController::class,'removeFromFavorites']);
Route::get('tasks/favorite',[TaskController::class,'getFavoriteTasks']);

Route::apiResource('center', CenterController::class)->middleware('isSuperAdmin');;
Route::post('/create-admin', [UserController::class, 'storeAdmin']);

Route::post('/admin/create-ceo-user', [AdminUserController::class, 'store']);
Route::post('/ceo/create-user', [CeoUserController::class, 'store']);


Route::post('/patient', [PatientController::class, 'store']);

Route::post('/drug', [DrugController::class, 'store']);

Route::post('/health-caenter', [HealthCenterController::class, 'store']);

});
Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::post('logout',[UserController::class,'logout'])->middleware('auth:sanctum');