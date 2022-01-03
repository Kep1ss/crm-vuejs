<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FingerDevice;

class FingerDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            FingerDevice::create([
                "name" => "Nama Mesin",
                "address" => "192.168.1.1"                       
            ]);
        }
    }
}
