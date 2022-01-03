<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        for($i=0;$i<10;$i++){
            Position::create([
                "code" => "P".($i+1),
                "name" => "Position-".($i + 1)
            ]);
        }
    }
}
