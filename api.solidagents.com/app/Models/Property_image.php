<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property_image extends Model
{
    use HasFactory;

    public function property(){
        return $this->belongsTo('App\Models\Property');
    }
    public function getLinkAttribute($value){
        return URL($value);
    }
}
