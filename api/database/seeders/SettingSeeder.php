<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            "name" => "company_name",
            "value" => "mediatama"
        ]);

        Setting::create([
            "name" => "address",
            "value" => "Alamat"
        ]);

        Setting::create([
            "name" => "email",
            "value" => "mediatama@gmail.com"
        ]);

        Setting::create([
            "name" => "phone",
            "value" => "089786867576"
        ]);

        Setting::create([
            "name" => "logo",
            "value" => "defualt.jpeg"
        ]);
        
        Setting::create([
            "name" => "header_color",
            "value" => "lightblue"
        ]);

        Setting::create([
            "name" => "dapodik_url",
            "value" => "https://dapo.kemdikbud.go.id/rekap/progresSP"
        ]);

        Setting::create([
            "name" => "dapodik_school_id",
            "value" => "3"
        ]);
    }
}
