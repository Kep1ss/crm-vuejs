<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DownloadCatalog;

class DownloadCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<15;$i++){
            DownloadCatalog::create([
                "title" => "catalog-".$i,
                "link" => "http://localhost"
            ]);
        }
    }
}
