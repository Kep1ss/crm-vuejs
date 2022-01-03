<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermitFormula;

class PermitFormulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            PermitFormula::create([
                "payroll_parameter_id" => rand(1,5),
                "permit_type_id" => rand(1,5),
                "percent" => 0,
                "nominal" => 0
            ]);
        }
    }
}
