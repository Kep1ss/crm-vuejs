<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermitEmploye extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $guarded = [];

    public function employe(){
        return $this->belongsTo(Employe::class);
    }

    public function permit_type(){
        return $this->belongsTo(PermitType::class);
    }
}
