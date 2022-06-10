<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
   
   public function register(){
      $credentials = request(['name','email','password','screen_name','profile_image']);
      User::create([
          'screen_name' => $credentials['screen_name'],
          'name' => $credentials['name'],
          'email' => $credentials['email'],
	        'profile_image'=> $credentials['profile_image'],
          'password' => Hash::make($credentials['password'])
      ]);


       if(!$token = auth('api')->attempt($credentials)){
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       return $this->respondwithToken($token);
    }
  public function registerImage(Request $request){
        $file = $request->file('file');
	      $file_name = $file->getClientOriginalName();
	      $image_path = $file->storeAs('/', $file_name, 'profile_image');
  }
    
    function login() {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'ログアウトしました。']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth("api")->factory()->getTTL() * 60
        ]);
    }
}
