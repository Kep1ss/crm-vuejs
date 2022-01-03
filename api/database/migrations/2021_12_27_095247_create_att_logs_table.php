<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // attendances => absensi (dihapus)
        // att_logs => absensi (scan absensi)
            // scan_logs => crud
            // attendces  => cuma nampilin sp

        // finger_devices => finger print (not done)

        // national_holidays => libur nasional (done)
        // permit_types => jenis izin  (not done)
             // relasi permit_formula 
        // permit_employe => izin_karyawan  (done)

        Schema::create('att_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("employe_id")->nullable()->unsigned();
            $table->datetime("scan_date")->nullable();
            // Tanggal Scan Mesin sidik jari
            $table->integer("inoutmode")->nullable();
            // Jenis InOut (1,6)                
            $table->timestamps();
            // JENIS MASUK => PULAN,MASUK
            // HOUR
            $table->softDeletes();

            $table->foreign('employe_id')->references('id')->on('employes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('att_logs');
    }
}
