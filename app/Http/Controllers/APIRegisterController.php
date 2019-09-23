<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;
class APIRegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required',
            'file_url' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

 
 
 

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'file_url' =>$this->fileupload_fn($request->file('file_url')),
            'password' => bcrypt($request->get('password')),

        ]);

        $user = User::first();
        $token = JWTAuth::fromUser($user);
        
        return Response::json(compact('token'));
    }



  public function fileupload_fn($image){
     
  
            $declva='';
            if($image!=''){
            $name = time().'.'.$image->getClientOriginalName();
            $destinationPath = public_path('upload/'); //->save image folder 
            $image->move($destinationPath, $name);
            $declva=$name;
           }else
           {
              $declva='0';
           } 
             
             return $declva;
  }


}
