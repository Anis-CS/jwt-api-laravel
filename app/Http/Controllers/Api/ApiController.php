<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    //Post[name,email,password]
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user =User::Create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>bcrypt($request->password),
        ]);
        return response()->json([
            'status'=>true,
            'message'=>"User Register Successfully.",
            'data'=>[]
        ]);

    }
    //Post[email,password]
    public function login(Request $request){
        //validation
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);
        //auth Facade
        $token=Auth::attempt([
            "email"=>$request->email,
            "password"=>$request->password,
        ]);
        if (!$token){
            return response()->json([
                "status"=>false,
                "message"=>"Invalid Login Email and Password.",
            ]);
        }
        return response()->json([
            "status"=>true,
            "message"=>"Login Successfully.",
            "token"=>$token,
            "expires_in"=>auth()->factory()->getTTL() * 60,
        ]);
    }
    //Get[Auth:token]
    public function profile(){
        $userData = auth()->user();
        return response([
            "status"=>true,
            "message"=>"Profile Information",
            "user"=>$userData
        ]);
    }
    //Post[Request Auth:token]
    public function refreshToken(){
        $token=auth()->refresh();
        return response()->json([
            "status"=>true,
            "message"=>"Refresh Token Successfully.",
            "token"=>$token,
            "expires_in"=>auth()->factory()->getTTL() * 60,
        ]);
    }
    //Get[Auth:token]
    public function logout(){
        auth()->logout();
       return response()->json([
            'status'=>true,
            'message'=>"User Logout Successfully."
        ]);
    }
}
