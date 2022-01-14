<?php

namespace App\Classes;

use App\Models\{User, Property, House_detail, Land_detail, Property_image};
use Carbon\Carbon;
use Auth;

class PropertyClass
{
    public function get_property_images($property)
    {
        if ($property->image_count > 0) {
            return $property->property_image;
        }
    }
    public function process_desc($request_obj)
    { //process the description field from the db
        $desc_arr = explode(',', $request_obj->description);
        foreach ($desc_arr as $desc) {
            $new_desc_arr = explode(':', $desc);
            //Log::info($new_desc_arr[0] . ' and ' . $new_desc_arr[1]);
            $field_name = $new_desc_arr[0];
            $request_obj->$field_name  = $new_desc_arr[1];
            //Log::info($request_obj);
        }
        return $request_obj;
    }
}
