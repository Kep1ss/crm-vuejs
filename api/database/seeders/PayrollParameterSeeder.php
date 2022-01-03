<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PayrollParameter;

class PayrollParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parameter_type = ["Tunjangan","Potongan","Lain-Lain"];

        for($i=0;$i<10;$i++){
            PayrollParameter::create([
                "name" => "Payroll-Parameter-".($i + 1),
                "parameter_type" => $parameter_type[rand(0,2)]
            ]);
        }
    }
}
