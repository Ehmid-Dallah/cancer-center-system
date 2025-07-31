<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index()
    {
        $tasks=Auth::user()->tasks;
       // $task = Task::all();
        return response()->json($tasks, 200,);
    }
    public function store(StoreTaskRequest $request)
    {
        $user_id=Auth::user()->id;
        $validatedDate=$request->validated();
        $validatedDate['user_id']=$user_id;
        $task = Task::create($validatedDate);

        return response()->json($task, 201);
    }


    public function update(UpdateTaskRequest $request, $id)
    {
        $user_id=Auth::user()->id;
        $task = Task::findOrFail($id);
       if( $task->user_id != $user_id)
       return response()->json(['message'=>'unauthurized',], 403);

        $task->update($request->validated());
        return response()->json($task, 200);
    }
    public function show($id)
    {
        $task = Task::find($id);
        return response()->json($task, 200);
    }
    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
        
            return response()->json([
                'message' => 'Task deleted successfully'
            ], 200);
        } catch (Exception $m) {
            return response()->json([
                'error' => 'something went wrong',
                'details' => $m->getMessage()
            ], 404);
        }
       
    }


    public function getTaskUser($id)
    {
        $user = Task::findorfail($id)->user;
        return response()->json($user, 200,);
    }
    public function addCategoriesToTask(Request $request,$taskId) 
    {
        $task= Task::findOrFail($taskId);
        $task->categories()->attach($request->category_id);
        return response()->json('Catogery attached successfuly', 200,);

    }

    public function getCategoriesToTask($taskId) 
    {
        $categories=Task::findorfail($taskId)->categories;
        return response()->json($categories, 200,);
    }

    public function getAllTasks()
    {
        $tasks=Task::all();
       
        return response()->json($tasks, 200,);
    }

    public function getTaskByPriority()
    {
        $tasks=Auth::user()->tasks()->orderByRaw("FIELD(priority,'high','medium','low')")->get();
       // $task = Task::all();
        return response()->json($tasks, 200,);
    }

    public function addToFavorites($taskId)
    {
        Task::findOrFail($taskId);
     auth::user()->favoriteTasks()->syncWithoutDetaching($taskId);
     return response()->json(['message'=>'task added to favorites'],200);
    }

    public function removeFromFavorites($taskId)
    {
        Task::findOrFail($taskId);
        auth::user()->favoriteTasks()->detach($taskId);
        return response()->json(['message'=>'task remove from favorites'],200);
    }
    public function getFavoriteTasks($taskId)
    {

    }
}
