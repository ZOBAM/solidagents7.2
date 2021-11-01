<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallRequestController extends Controller
{
    public function request_call(Request $request)
    {
        return ['msg' => 'about to request for a call', 'formvalues' => $request->all()];
    }
}
