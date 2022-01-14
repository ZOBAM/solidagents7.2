<?php

namespace App\Classes;

use App\Mail\VerifyEmail;
use App\Models\Call_request;
use App\Models\Property_request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserClass
{
    private $user;
    public function __construct($user_id)
    {
        $this->user = User::find($user_id);
    }
    public function get_request($type = 'request')
    {
        if ($type == 'call')
            return  Call_request::where('user_id', $this->user->id)->get();
        return Property_request::where('user_id', $this->user->id)->get();
    }
    public function send_verification_code($type = 'email')
    {
        $code = mt_rand(100000, 999999);
        if ($type == 'email') {
            if (strpos($this->user->tel, ',')) {
                $prev_code = substr($this->user->tel, -6, 6);
                $this->user->tel = str_replace($prev_code, $code, $this->user->tel);
                $this->user->save();
                //Log::info('value of prev code is ' . $prev_code);
            } else {
                $this->user->tel .= ',' . $code;
                $this->user->save();
                //Log::info('sending verification ' . $code . ' email to ' . $this->user->email);
            }
            Mail::to($this->user)->send(new VerifyEmail($code));
        }
    }
    public function verify($code, $type = 'email')
    {
        if ($type == 'email') {
            $code_arr = explode(',', $this->user->tel);
            $user_code = $code_arr[1];
            if ($user_code == $code) {
                $this->user->tel = $code_arr[0];
                $this->user->email_verified_at = Carbon::now();
                $this->user->save();
                return true;
            }
            return false;
        }
    }
}
