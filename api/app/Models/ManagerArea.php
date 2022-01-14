<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ManagerArea extends Model
{
    use HasFactory;

    protected $table='v_manager_area';

    public function parent(){
        return $this->belongsTo(User::class);
    }
}
