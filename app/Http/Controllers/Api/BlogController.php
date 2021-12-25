<?php

namespace App\Http\Controllers\Api;

use Faker\Factory;
use App\Blog;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index(){
        $blogs = Blog::all();
        return response()->json([
            'blogs' => BlogResource::collection($blogs)
        ],200);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            //using helpers function 
            return send_error('Validation Error', $validator->errors(), 422);
        }   


        try{
           
          $blog =   Blog::create([
                        'title' => $request->title,
                        'description' => $request->description
                    ]);

            $data =[
                'title' => $blog->title,
                'description' => $blog->description
            ];

            return send_success('Blog Added Successfully',$data,200);
        }
        catch(Exception $e){

            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());

            return send_error($e->getMessage(), $e->getCode());
        }

       


    }

    public function show($id){
        $blog = Blog::where('id',$id)->first();
        if($blog){
            return response()->json([
                'blog' => new BlogResource($blog),
            ],200);
        }
        else{
            return response()->json([
                'message' => 'Blog not Found'
            ],401); 
        }
       
    }

    public function update(Request $request,$id){

       

        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            //using helpers function 
            return send_error('Validation Error', $validator->errors(), 422);
        }
        try{
           
            Blog::where('id',$id)->update([
                'title' => $request->title,
                'description' => $request->description
            ]);
  
            $data =[
                'title' => $request->title,
                'description' => $request->description
            ];

  
              return send_success('Blog update Successfully',$data,200);
          }
          catch(Exception $e){
  
              return response()->json([
                  'message' => $e->getMessage()
              ], $e->getCode());
  
              return send_error($e->getMessage(), $e->getCode());
          }
  
    }

    public function destroy($id){
        $blog = Blog::where('id',$id)->first();
        if($blog){
            try{
                $blog->delete();
                return send_success('Blog Delete Successfully');
            }
            catch(Exception $e){
                return response()->json([
                    'message' => $e->getMessage()
                ], $e->getCode());
    
                return send_error($e->getMessage(), $e->getCode());
            }
           
        }
        else{
            return response()->json([
                'message' => 'Blog not Found'
            ],401); 
        }
       
    }
}
