<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Employe,
    EmployePayrollParameter as ModelEmployePayrollParameter,
    PayrollParameter
};

class EmployePayrollParameter extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<30;$i++){        
            $payroll_methods = ["Tetap","Harian","Bulanan"];
            $employe = Employe::inRandomOrder()->first();
            $payrollParameter = PayrollParameter::inRandomOrder()->first();

            ModelEmployePayrollParameter::create([
              "payroll_parameter_id" => $payrollParameter->id,
              "employe_id" => $employe->id,
              "payroll_method" => $payroll_methods[rand(0,2)],
              "workday" => rand(1,30),
              "percentage" => rand(10,50),
              "amount" => rand(1000000,99999999)
            ]);
        }
    }
}
