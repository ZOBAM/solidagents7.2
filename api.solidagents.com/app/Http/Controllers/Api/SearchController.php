<?php

namespace App\Http\Controllers\api;

use App\Classes\PropertyClass;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search()
    {
        //return ['message' => "reached search api on the server!"];
        $query_str = $_GET['query'];
        $search_results = Property::search($query_str)->get();
        if (count($search_results)) {
            $property_class = new PropertyClass;
            foreach ($search_results as $property) {
                $property->images = $property_class->get_property_images($property);
            }
        }
        return $search_results;
    }
}
