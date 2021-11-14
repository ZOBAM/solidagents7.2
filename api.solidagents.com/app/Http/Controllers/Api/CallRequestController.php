<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Call_request;
use Illuminate\Http\Request;

class CallRequestController extends Controller
{
    public function request_call(Request $request)
    {
        $user_active_request = Call_request::where('user_id', $request->user()->id)->where('done', false)->first();
        if ($user_active_request) {
            return ['status' => 2, 'message' => 'You already sent a call request and it is still active. Please be patient, you shall be reached soon.'];
        }
        $this->validate($request, [
            'propertyType'    => 'required|string|min:2|max:20',
            'dealType'     => 'required|string|min:4|max:12',
            'callTime'           =>  'required|string|min:5|max:50',
            'name'         => 'required|string|min:5|max:29',
            'tel'      => 'required|string|min:3|max:15', /* */
        ]);
        $request_call = new Call_request();
        $request_call->user_id = $request->user()->id;
        $request_call->description = $request->propertyType . ' for ' . $request->dealType;
        $request_call->ip_address = $request->ip();
        $request_call->tel = $request->tel;
        $request_call->name = $request->name;
        $request_call->preferred_time = $request->callTime;
        $request_call->save();
        return ['status' => 1, 'message' => 'Your call request has been received. Call you soon.'];
    }
}
