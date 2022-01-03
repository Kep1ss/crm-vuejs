<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IndexFormula;

class IndexFormulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            IndexFormula::create([
                "name" => "formula-".$i,
                "value" => $i*10000
            ]);
        }
    }
}
