<?php

namespace App\Classes;

use App\Models\Call_request;
use App\Models\Property_request;
use App\Models\User;

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
}
