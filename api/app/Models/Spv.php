<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spv extends Model
{
    use HasFactory;

    protected $table='v_spv';

    public function parent(){
        return $this->belongsTo(User::class);
    }
}
