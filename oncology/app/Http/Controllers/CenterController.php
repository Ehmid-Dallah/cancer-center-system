<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCenterRequest;
use App\Models\Center;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CenterController extends Controller
{
    public function index()
    {
        $center=Auth::user()->tasks;
       // $task = Task::all();
        return response()->json( $center, 200,);
    }
    /*
    public function store(StoreCenterRequest $request)
    {
        $user_id=Auth::user()->id;
        $validatedDate=$request->validated();
        $validatedDate['user_id']=$user_id;
        $center = Center::create($validatedDate);

        return response()->json($center, 201);
    }*/

  
    public function store(StoreCenterRequest $request)
    {
       if (Auth::user()->role !== 'superadmin') {
            return response()->json(['message' => 'غير مصرح لك!'], 403);
        }
    
        $request->validate([
            'name' => 'required|string',
            'admin_id' => 'required|exists:users,id', // تأكد إن الـ admin موجود
        ]);
    
        $center = Center::create([
            'name' => $request->name,
            'user_id' => $request->admin_id, // نربط المركز بالـ admin المحدد
        ]);
    
        return response()->json($center, 201);
    }
    


    public function update(StoreCenterRequest  $request, $id)
    {
        $user_id=Auth::user()->id;
        $center= center::findOrFail($id);
       if(  $center->user_id != $user_id)
       return response()->json(['message'=>'unauthurized',], 403);

       $center->update($request->validated());
        return response()->json( $center, 200);
    }
    public function show($id)
    {
        $center = center::find($id);
        return response()->json( $center, 200);
    }
    public function destroy($id)
    {
        try {
            $center = Center::findOrFail($id);
            $center->delete();
        
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

    //
}
