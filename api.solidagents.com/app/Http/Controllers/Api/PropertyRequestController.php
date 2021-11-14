<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property_request;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PropertyRequestController extends Controller
{
    public function request_property(Request $request)
    {
        $user_id = $request->user()->id;
        //check if user has requested for property today
        $today_request = Property_request::where('user_id', $user_id)->whereDate('created_at', Carbon::today())->first();
        if ($today_request) {
            return ['status' => 2, 'message' => 'You have reached the permitted limit for today. Please, try again tomorrow.'];
        }
        $this->validate($request, [
            'propertyType'    => 'required|string|min:2|max:20',
            'dealType'     => 'required|string|min:4|max:12',
            'purpose'           =>  'nullable|string|min:5|max:15',
            'houseType'         => 'nullable|string|min:4|max:19',
            'state'      => 'required|string|min:3|max:15',
            'lga'      => 'required|string|min:3|max:15',
            'town'      => 'required|string|min:3|max:25',
            'lowestPrice'      => 'required|numeric|min:1000|max:10000000000',
            'highestPrice'      =>  'required|numeric|min:1000|max:10000000000',
            'detail' => 'nullable|min:3|max:255', /* */
        ]);
        $property_request = new Property_request;
        $property_request->user_id = $user_id;
        $property_request->type = $request->propertyType;
        $property_request->deal = $request->dealType;
        $property_request->description = $request->purpose;
        $property_request->detail = $request->detail ?? '';
        $property_request->state = $request->state;
        $property_request->lga = $request->lga;
        $property_request->town = $request->town;
        $property_request->lowest_price = $request->lowestPrice;
        $property_request->highest_price = $request->highestPrice;
        $property_request->description = $request->houseType ? 'house_type:' . $request->houseType : 'purpose:' . $request->purpose . ',plots:' . $request->plots;
        $property_request->save();
        return ['status' => 1, 'message' => 'Your request has been submitted. You will soon get a deal.'];
    }
}
