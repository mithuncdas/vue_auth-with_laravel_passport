<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){

    }
    public function register(Request $request){

       $validator =  Validator::make($request->all(),[
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ],422);
        }

        try{
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('12345678')
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User registration Success',
                'name' => $user->name
            ]);

        } 
        catch(Exception $e){

            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());

        }

        return response()->json([
            'message' => 'Registration Successfull'
        ],200);
    }
}
