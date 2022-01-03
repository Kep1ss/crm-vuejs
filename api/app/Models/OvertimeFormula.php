<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OvertimeFormula extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];   

    public function overtime_category(){
        return $this->belongsTo(OvertimeCategory::class);
    }

    public function index_formula(){
        return $this->belongsTo(IndexFormula::class);
    }
}
