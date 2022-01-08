<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DumpDapodikSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::unprepared(file_get_contents(__DIR__.'/dapodik.sql'));
    }
}
