<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyRequestController extends Controller
{
    public function request_property(Request $request)
    {
        return ['msg' => 'about to request for property', 'formvalues' => $request->all()];
    }
}
