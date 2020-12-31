<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
    //login user via passport
    public function login(Request $request){
        //return ['email'=> $request->email];
        $login = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string'
        ]);
        if(! Auth::attempt($login)){
            return response(['message' => 'Invalid login credentials']);
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        Auth::user()->dp_link = URL(Auth::user()->dp_link); //formate the link for json
        return response ([
            'user' => Auth::user(), 'access_token' => $accessToken
        ]);
    }
    //return all users
    public function index(){
        return User::get();
    }

}
