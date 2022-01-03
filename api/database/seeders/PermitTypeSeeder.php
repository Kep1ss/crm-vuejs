<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermitType;

class PermitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            PermitType::create([
                "name" => "Jenis Izin ".$i
            ]);
        }
    }
}
