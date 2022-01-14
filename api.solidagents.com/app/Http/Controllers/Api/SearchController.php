<?php

namespace App\Http\Controllers\Api;

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
        //filter by price range
        if (isset($_GET['highestPrice']) || isset($_GET['lowestPrice'])) {
            Log::info('low or high found');
            //trying by populating an empty array
            $new_results = [];
            $highestPrice = $_GET['highestPrice'] ?? 10000000000;
            $lowestPrice = $_GET['lowestPrice'] ?? 10000;
            foreach ($search_results as $result) {
                if (!(($result->price < $lowestPrice) || ($result->price > $highestPrice))) {
                    $new_results[] = $result;
                }
            }
            $search_results = $new_results;
        }
        //filter by dealType
        if (isset($_GET['dealType'])) {
            $new_results = [];
            foreach ($search_results as $result) {
                if ($result->deal == 'for_' . strtolower(str_replace(' ', '_', $_GET['dealType']))) {
                    $new_results[] = $result;
                }
            }
            $search_results = $new_results;
        }
        //filter by property type
        if (isset($_GET['propertyType'])) {
            $new_results = [];
            foreach ($search_results as $result) {
                if ($result->type == strtolower($_GET['propertyType'])) {
                    $new_results[] = $result;
                }
            }
            $search_results = $new_results;
            /* Log::info('property type is set');
            $search_results = $search_results->reject(function ($result) {
                return $result->type != strtolower($_GET['propertyType']);
            });
            Log::info($search_results); */
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
