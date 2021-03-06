<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Classes\{UploadClass, UserClass};
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function signup(Request $request)
    {
        $this->validate($request, [
            'first_name'    => 'required|string|min:2|max:25',
            'last_name'     => 'required|string|min:2|max:25',
            'tel'           =>  'required|string|min:11|max:15|unique:users',
            'email'         => 'required|string|email|max:55|unique:users',
            'password'      => 'required|string|min:8',
        ]);
        $user = new User;
        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->tel          = $request->tel;
        $user->email        = $request->email;
        $user->password     = Hash::make($request->password);
        if ($user->save()) {
            if ($request->file('dp')) {
                $uploadClass = new UploadClass('images/profile_images/', $user->id);
                $uploadClass->upload($request->file('dp'));
            }
            $login = $request->validate([
                'email' => 'required|email|string',
                'password' => 'required|string'
            ]);
            if (Auth::attempt($login)) {
                Auth::user()->dp_link = URL(Auth::user()->dp_link); //formate the link for json
                $user = Auth::user();
                $accessToken = Auth::user()->createToken('authToken')->accessToken;
                //send verification code to email
                $user_class = new UserClass($user->id);
                $user_class->send_verification_code();
                return ['status' => 1, 'response' => "Account Created successfully", 'user' => $user, 'access_token' => $accessToken];
            } else {
                return ['status' => 2, 'response' => "Account Created successfully but could not sign in"];
            }
        }
    }
}
