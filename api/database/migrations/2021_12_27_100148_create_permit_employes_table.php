<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermitEmployesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permit_employes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("employe_id")->nullable()->unsigned();
            $table->bigInteger("permit_type_id")->nullable()->unsigned();
            $table->string("description",150)->nullable();
            // Keterangan 
            $table->date("permit_date_start")->nullable();
            // Tanggal Awal Ijin
            $table->date("permit_date_end")->nullable();
            // Tanggal Akhir Ijin
            $table->integer("days_permit")->nullable();
            // Jumlah Hari Ijin
            $table->foreign('employe_id')->references('id')->on('employes');
            $table->foreign('permit_type_id')->references('id')->on('permit_types');         
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permit_employes');
    }
}
