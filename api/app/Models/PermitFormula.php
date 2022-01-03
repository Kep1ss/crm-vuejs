<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitFormula extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function payroll_parameter(){        
        return $this->belongsTo(PayrollParameter::class);
    }
}
