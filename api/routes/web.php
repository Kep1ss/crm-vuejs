<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/api/v1/status');
});

Route::get("/getDataSchoolDapodik",function(){
    $url = "https://dapo.kemdikbud.go.id/rekap/progresSP";
    $id_level_wilayah = "3";
    $kode_wilayah = "056026";
    $year = "2021";
    $semester_id = "1";
    $bentuk_pendidikan_id = "sd";
    
    $compelete_url = $url .
         "?id_level_wilayah=" . $id_level_wilayah .
         "&kode_wilayah=" . $kode_wilayah .
         "&semester_id=" . $year . $semester_id . 
         "&bentuk_pendidikan_id=" . $bentuk_pendidikan_id;

    $response = Http::get($compelete_url);
    echo $response->body();
});