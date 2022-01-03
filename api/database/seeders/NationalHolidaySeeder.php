<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NationalHoliday;

class NationalHolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            NationalHoliday::create([
                "name" => "Tahun Baru",
                "off_date" => "2020-01-01"                     
            ]);
        }
    }
}
