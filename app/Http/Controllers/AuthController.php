<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api", ["except" => ["login"]]);
        return auth()->shouldUse("api");
    }

    public function login(){
        $credentials=request(["email","password"]);

        if(!$token=auth("api")->attempt($credentials)){
            return response()->json(["error" => "Unauthorized"], 401);
        }
        return $this->respondWithToken($token);
    }

    public function me(){
        return response()->json(auth()->user());
    }

    public function logout(){
        auth()->logout();
        return response()->json(["message" => "Successfully logged out"]);
    }

    public function refresh(){
        // parameter pertama nonaktif token yang lama, parameter ke dua mereset claim token yang baru
        return $this->respondWithToken(auth()->refresh(true, true));
    }

    public function respondWithToken($token){
        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" => auth()->factory()->getTTL()*60
        ]);
    }
}
