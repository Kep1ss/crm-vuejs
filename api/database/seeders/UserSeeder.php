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
        User::create([
            "username" => "superadmin",
            "fullname" => "super admin",
            "email" => "superadmin@gmail.com",
            "password" => \Hash::make("password"),
        ]);        
    }
}
