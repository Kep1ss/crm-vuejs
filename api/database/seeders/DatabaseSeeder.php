<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PayrollParameterSeeder::class,
            PositionSeeder::class,
            DivisionSeeder::class,
            EmployeSeeder::class,
            EmployePayrollParameter::class,
            SettingSeeder::class,

            IndexFormulaSeeder::class,
            OvertimeCategorySeeder::class,
            OvertimeFormulaSeeder::class,            

            AttLogSeeder::class,
            FingerDeviceSeeder::class,
            NationalHolidaySeeder::class,
            PermitTypeSeeder::class,
            PermitEmployeSeeder::class,
            PermitFormulaSeeder::class
        ]);
    }
}
