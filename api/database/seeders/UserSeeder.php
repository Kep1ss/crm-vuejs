<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<30;$i++){
            User::create([
                "username" => "user".$i,
                "fullname" => "user".$i,
                "email" => "user".$i."@gmail.com",
                "password" => \Hash::make("password"),
            ]);
        }
    }
}
