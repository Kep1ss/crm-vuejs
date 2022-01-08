<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getValueAttribute($value){
        if(isset($this->attributes["name"])){
            return $this->attributes["name"] == "logo"
                ? asset("/images/logos/default.jpeg")
                : $value;
        }else{
            return $value;
        }
    }

    public function getValueOriginalAttribute($value){
        return $this->attributes["value"];
    }
}
