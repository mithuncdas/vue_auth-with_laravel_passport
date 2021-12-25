<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function index(){
        $user = User::all();
        return response()->json([
            'user' => $user,
        ],200);
    }
    public function login(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){

            //using helpers function 
            return send_error('Validation Error', $validator->errors(), 422);
        }

        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            
            $data['name'] = $user->name;
            $data['access_token'] = $user->createToken('accessToken')->accessToken;

            return send_success('User login Success',$data);

            
        }
        else{
            return send_error('Unauthorized Entry',401);
        }
        

        
      

    }

  
    
    public function register(Request $request){

       $validator =  Validator::make($request->all(),[
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()){

            //for individula response
            // return response()->json([
            //     'message' => 'Validation Error',
            //     'data' => $validator->errors()
            // ],422);

            //using helpers function 

            return send_error('Validation Error', $validator->errors(), 422);
        }

        try{
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('12345678')
            ]);

            //for individual use

            // return response()->json([
            //     'status' => true,
            //     'message' => 'User registration Success',
            //     'name' => $user->name
            // ]);


            //using helpers function

            $data =[
                'name' => $user->name,
                'email' => $user->email
            ];

            return send_success('User Registration Success',$data);

        } 
        catch(Exception $e){

            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());

            return send_error($e->getMessage(), $e->getCode());

        }

        return response()->json([
            'message' => 'Registration Successfull'
        ],200);
    }

    public function logout(Request $request){
        auth()->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully Logout'
        ]);
    }

    public function show($id){
        $user = User::where('id',$id)->first();
        if($user){
            return response()->json([
                'status' => true,
                'message' => 'User Found',
                'user' => $user,
            ],200);
        }   
        else{
            return response()->json([
                'status' => false,
                'message' => 'User Not Found'
            ],401);
        }
    }
}
