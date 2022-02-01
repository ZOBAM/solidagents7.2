<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function create(Request $request)
    {
        return ['msg' => 'reached the notification api'];
    }
    public function get_notifications($user_id)
    {
        return ['msg' => 'fetching user notifications'];
    }
}
