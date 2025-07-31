<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    public function index()
    {
        $users = [
            ['id' => 1, 'name' => "ahmed"],
            ['id' => 2, 'name' => "omar"],
            ['id' => 3, 'name' => "ehmid"],
        ];
        //foreach($users as $user){
        //    echo $user['id'];
        //  }
        return response()->json($users);
    }
    public function CheckUser(){
        return response()->json('yes');
    }
    //
    */
    public function getProfile($id)
    {
        $profile = User::find($id)->profile;
        return response()->json($profile, 200,);
    }
    public function getUserTasks($id)
    {
        $tasks = User::findorfail($id)->tasks;
        return response()->json($tasks, 200,);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'message' => 'user registered successfully',
            'user' => $user
        ], 201);


    }
    public function login(Request $request) 
    {
        $request->validate([
            
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if(!Auth::attempt($request->only('email','password')))
        return response()->json([
           'message'=>'invalid email or password'],401);
          $user= User::where('email',$request->email)->firstOrFail();
          $token=$user->createToken('auth_Token')->plainTextToken;
          return response()->json([
            'message' => 'login successfully',
            'user' => $user, 
            'token'=>$token
        ], 201);
    }
    public function logout(Request $request) 
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'logout successfully',
        ], 201);
    }
    public function GetUser()
    {
        $user_id=Auth::user()->id;
        $userData=user::with('profile')->findOrFail($user_id);
        //$userData=user::with('profile')->get();
        return new UserResource($userData);
       //return  UserResource::collection($userData);
    }



    public function storeAdmin(Request $request)
    {
        // التحقق من أن المستخدم الحالي هو superadmin
        if (Auth::user()->role !== 'superadmin') {
            return response()->json(['message' => 'غير مصرح لك!'], 403);
        }

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // إنشاء مستخدم جديد بنوع admin
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
           
        ]);

        return response()->json($admin, 201);
    }
}
