<?php

namespace App\Http\Controllers\Api;

use App\Classes\UserClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function verify(Request $request)
    {
        $user_id = $request->user()->id;
        $user = new UserClass($user_id);
        if ($request->resend) {
            //resend verification code in email
            $user->send_verification_code();
            return ['status' => 2, 'msg' => 'code resent to your email'];
        } else {
            if ($request->type == 'email') {
                //verify email.
                if ($user->verify($request->code))
                    return ['status' => 1, 'msg' => 'your email is verified'];
                return ['status' => 0, 'msg' => 'Invalid code. Please check well and try again.', 'user' => $request->user()];
            } else {
                //verify phone number
                return ['msg' => 'your phone number is verified'];
            }
        }
    }
}
