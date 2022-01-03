<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OvertimeCategory;

class OvertimeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            OvertimeCategory::create([
                "name" => "overtime-category-".$i
            ]);
        }
    }
}
