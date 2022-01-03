<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttLog;

class AttLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            $employe = rand(1,5);

            AttLog::create([
                "employe_id" => $employe,
                "scan_date" => now()->setTime(7,0,0)->toDateTimeString(),
                "inoutmode" => 1
            ]);

            AttLog::create([
                "employe_id" => $employe,
                "scan_date" => now()->setTime(16,0,0)->toDateTimeString(),
                "inoutmode" => 4
            ]);
        }
    }
}
