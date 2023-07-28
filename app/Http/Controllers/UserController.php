<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function login(Rquest $request){
        try{
           $user= User::where("email",$request->email)->first(); 
            if($user){
                if(Auth::attempt(["email" => $request->email, "password"=>$request->password]))
                {
                    $token=$super->createToken("Login app")->accessToken;
                    return response()->json(["sucess"=>true=>$token, "user"=>$user])
                }
                return response()->json("success"=>false, "message"=>"Usuario y/o contraseña incorrectas");
            }   
            return response()->json("success"=>false, "message"=>"Usuario y/o contraseña incorrectas");
        }catch(Exception $e){
            return response()->json(["success"=>false,"message"=>$e=>getMessage()], 500)
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
