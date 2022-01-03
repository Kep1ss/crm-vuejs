<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermitEmploye;

class PermitEmployeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            PermitEmploye::create([
                "employe_id" => rand(1,5),
                "permit_type_id" => rand(1,5),
                "description" => "Deskripsi",
                "permit_date_start" => "2020-01-01",
                "permit_date_end" => "2020-01-12",
                "days_permit" => 5
            ]);
        }
    }
}
