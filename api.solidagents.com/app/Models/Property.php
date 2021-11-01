<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Property extends Model
{
    use Searchable;
    use HasFactory;
    protected $table = "properties";

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function property_image()
    {
        return $this->hasMany('App\Models\Property_image');
    }
}
