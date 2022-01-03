<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Employe,
    Position,
    Division
};

class EmployeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        for($i=0;$i<30;$i++){
            $genders = ["L","P"];
            $citys = ["Surkarta","Sukharjo","Semarang"];        
            $academics = ["SMK","S1","S2","S3","D3"];

            $division = Division::inRandomOrder()->first();
            $position = Position::inRandomOrder()->first();

            Employe::create([
                "code" => "12345678".rand(100,999) . $i ,
                "citizen_id" => "123456".rand(100,900). $i,
                "name" => "Employ-".($i+1),
                "gender" => $genders[rand(0,1)],
                "address" => "Addres Employe ".($i+1),
                "city" => $citys[rand(0,2)],
                "birth_day" => now()->addDays($i)->addYears(-20)->format("Y-m-d"),
                "graduate" => $academics[rand(0,4)],
                "phone" => "+620897867".rand(100,999),
                "division_id" => $division->id,
                "position_id" => $position->id,
                "join_date" => now()->addDays($i)->addYears(-5)->format("Y-m-d"),
                "resigned" => now()->addDays($i)->format("Y-m-d"),
                "npwp" => "123456789".rand(100,999)
            ]);
        }
    }
}
