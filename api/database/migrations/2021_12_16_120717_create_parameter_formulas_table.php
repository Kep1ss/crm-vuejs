<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParameterFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_parameter_formulas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("payroll_parameter_id")->unsigned()->nullable();
            $table->text("formula")->nullable();        
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('payroll_parameter_id')->references('id')->on('payroll_parameters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameter_formulas');
    }
}
