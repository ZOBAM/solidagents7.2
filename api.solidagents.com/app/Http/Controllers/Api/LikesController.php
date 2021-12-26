<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Property;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function like(Request $request)
    {
        $user_id = $request->userID;
        $property_id = $request->propID;
        $property = Property::find($property_id);
        $liked = Like::where('user_id', $user_id)->where('property_id', $property_id)->first();
        if ($liked) {
            Like::destroy($liked->id);
            $property->likes--;
            $property->save();
            $response = ['msg' => 'you have unliked this property'];
        } else {
            $like = new Like;
            $like->user_id = $user_id;
            $like->property_id = $property_id;
            $like->save();
            $property->likes++;
            $property->save();
            $response = ['msg' => 'you have liked this property'];
        }
        return $response;
    }
}
