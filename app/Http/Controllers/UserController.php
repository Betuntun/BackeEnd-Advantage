<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function login(Request $request){
        try{
           $user= User::where("email",$request->email)->first(); 
            if($user){
                if(Auth::attempt(["email" => $request->email, "password"=>$request->password]))
                {
                    $token=$user->createToken("Login app")->accessToken;
                    return response()->json(["sucess"=>true,"token"=>$token, "user"=>$user]);
                }
                return response()->json(["success"=>false, "message"=>"contraseña incorrecta"]);
            }   
            return response()->json(["success"=>false, "message"=>"Usuario incorrecto"]);
        }catch(Exception $e){
            return response()->json(["success"=>false,"message"=>$e->getMessage()], 500);
        }
    }

    public function user(Request $request){
        try{
            return response()->json(["success"=>true, "user"=>$request->user()]);
        }catch(Exception $e){
            return response()->json(["success"=>false, "message"=>$e->getMessage()],500);
        }

    }
}
