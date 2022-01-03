<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employe extends Model
{
    use HasFactory,SoftDeletes;    

    protected $guarded = [];
    
    public function division(){
        return $this->belongsTo(Division::class);
    }

    public function position(){
        return $this->belongsTo(Position::class);
    }

    public function employe_payroll_parameters(){
        return $this->hasMany(EmployePayrollParameter::class);
    }
}
