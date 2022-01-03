<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployePayrollParameter extends Model
{
    protected $table = "employe_has_payroll_parameters";
    
    use HasFactory,SoftDeletes;
    
    protected $guarded = [];

    public function payroll_parameter(){
        return $this->belongsTo(PayrollParameter::class);
    }

    public function employe(){
        return $this->belongsTo(Employe::class);
    }
}
