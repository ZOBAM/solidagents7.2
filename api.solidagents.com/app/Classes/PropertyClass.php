<?php

namespace App\Classes;
use App\Models\{User, Property, House_detail, Land_detail, Property_image};
use Carbon\Carbon;
use Auth;

class PropertyClass {
    public function get_property_images($property){
        if($property->image_count>0){
            return $property->property_image;
        }
    }
}
