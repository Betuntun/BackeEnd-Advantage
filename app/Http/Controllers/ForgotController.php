<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ResetRequest;
use Illuminate\Support\Facades\Hash;

class ForgotController extends Controller
{
    public function forgot(Request $request){
        $email = $request->input('email');

        if(User::where('email', $email)->doesntExist()){
            return response([
                'message' => 'El usuario no existe'
            ], 400);
        }

        $token = Str::random(6);
        try{

            if($passwordResets = DB::table('password_reset_tokens')->where('email', $email)->first()){

                Mail::send('Mail.forgot', ['token'=> $passwordResets->token], function( $message) use ($email){
                    $message->to($email);
                    $message->subject('Cambia tu contraseña');
                 });

                 return response([
                    'message' => 'Revisa tu email'
                ]);
            }
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token
            ]);
                 //Eviar email
                 Mail::send('Mail.forgot', ['token'=> $token], function( $message) use ($email){
                    $message->to($email);
                    $message->subject('Cambia tu contraseña');
                 });
            return response([
                'message' => 'Revisa tu email'
            ]);
        } catch (\Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ],400);
        }

    }

    public function reset(ResetRequest $request){



        $token = $request->input('token');

        if(!$passwordResets = DB::table('password_reset_tokens')->where('token', $token)->first()){
            return response([
                'message' => 'Token Invalido!'
            ], 400);
        }

        if(!$user = User::where('email', $passwordResets->email)->first()){
            return response([
                'message' => 'El Usuario no existe'
            ], 400);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response([
            'message' => 'success'
        ]);

    }
}
