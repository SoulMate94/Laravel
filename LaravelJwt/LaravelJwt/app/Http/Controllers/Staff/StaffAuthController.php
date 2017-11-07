<?php

namespace App\Http\Controllers\Staff;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatestAndRegistersUsers;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    use AuthenticatestAndRegistersUsers, ThrottlesLogins;
    protected $guard = 'staffs';

    public function register(Request $req)
    {
        $this->validate($req,[
            'phone'    =>  'required|max:16',
            'password' =>  'required|min:6',
        ]);
        $credentials = [
            'phone'    =>  $req->input('phone'),
            'password' =>  bcrypt($req->input('password')),
        ];
        $id = Staff::create($credentials);
        if ($id) {
            $token = Auth::guard($this->getGuard())->attept($credentials); // 也可以直接guard('staffs')
            return response()->json(['result'=>$token]);
        }
    }

    public function login(Request $req)
    {
        $credentials = $req->only('phone', 'password');
        if ($token = Auth::guard($this->getGuard())->attempt($credentials)) {
            return response()->json(['result'=>$token]);
        } else {
            return response()->json(['result'=>false]);
        }
    }
}