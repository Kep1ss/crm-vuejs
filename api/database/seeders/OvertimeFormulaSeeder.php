<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OvertimeFormula;

class OvertimeFormulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            OvertimeFormula::create([
                "overtime_category_id" => rand(1,5),
                "index_formula_id" => rand(1,5),
                "formula" => "5 + 5"        
            ]);
        }
    }
}
