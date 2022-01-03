<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermitFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permit_formulas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("payroll_parameter_id")->nullable()->unsigned();
            $table->bigInteger("permit_type_id")->nullable()->unsigned();
            $table->decimal("percent",10,2)->nullable();
            // Presentase Potong Gaji
            $table->decimal("nominal",20,2)->nullable();
            // Nominal Potong Gaji
            $table->timestamps();
            // $table->softDeletes();

            $table->foreign('payroll_parameter_id')->references('id')->on('payroll_parameters');
            $table->foreign('permit_type_id')->references('id')->on('permit_types');                     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permit_formulas');
    }
}
