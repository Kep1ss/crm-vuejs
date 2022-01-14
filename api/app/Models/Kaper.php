<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaper extends Model
{
    use HasFactory;

    protected $table='v_kaper';

    public function parent(){
        return $this->belongsTo(User::class);
    }
}
