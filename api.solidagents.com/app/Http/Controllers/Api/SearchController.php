<?php

namespace App\Http\Controllers\api;

use App\Classes\PropertyClass;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search()
    {
        //return ['message' => "reached search api on the server!"];
        $query_str = $_GET['query'];
        $search_results = Property::search($query_str)->get();
        if (isset($_GET['state'])) {
            Log::info('state is set');
            $search_results = $search_results->reject(function ($result) {
                Log::info($result);
                return $result->state != $_GET['state'];
            });
            Log::info($_GET['state']);
            if (isset($_GET['lga'])) {
                $search_results = $search_results->reject(function ($result) {
                    return $result->lga != $_GET['lga'];
                });
            }
            if (isset($_GET['town'])) {
                $search_results = $search_results->reject(function ($result) {
                    return $result->town != $_GET['town'];
                });
            }
        }
        if (count($search_results)) {
            $property_class = new PropertyClass;
            foreach ($search_results as $property) {
                $property->images = $property_class->get_property_images($property);
            }
        }
        return $search_results;
    }
}
