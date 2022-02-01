<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    //this code here was just added to test out a code challenge and has nothing to do with this project.
    $path = "/../../foo/bar/..";
    function shorten_path($path)
    {
        $shortened_path = "";
        if ($path != "") {
            if (strpos($path, '../') != true) {
                $shortened_path = $path;
                return $shortened_path;
            } else {
                if (strpos($path, '/') == 0) {
                    //absolute path
                    if (strpos($path, '/../..') == 0) {
                        $shortened_path = str_replace('/../..', '', $path);
                    } else {
                        $path_arr = explode('/', $path);
                    }
                } else {
                    //relative path
                }
            }
        } else {
            return "Can't shorten empty string.";
        }
        return $shortened_path;
    }
    return shorten_path($path);
});
