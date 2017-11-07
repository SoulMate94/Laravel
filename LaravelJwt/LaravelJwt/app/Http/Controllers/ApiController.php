<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function register(Request $req)
    {
        $input = $req->all();
        $input['password'] = Hash::make($input['password']);
        User::create($input);
        return response()->json(['result'=>true]);
    }

    public function login(Request $req)
    {
        $input = $req->all();
        if (!$token = JWTAuth::attempt($input)) {
            return response()->json(['result'=>'Email or Password fail']);
        }
        return response()->json(['result'=>$token]);
    }

    public function get_user_details(Request $req)
    {
        $input = $req->all();
        $user = JWTAuth::toUser($input['token']);
        return response()->json(['result' => $user]);
    }
}
