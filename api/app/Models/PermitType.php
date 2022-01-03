<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermitType extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $guarded = [];

    public function permit_formulas(){
        return $this->hasMany(PermitFormula::class);
    }
}
