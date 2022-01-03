<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployePayrollParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employe_has_payroll_parameters', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger("payroll_parameter_id")->nullable()->index();
            $table->unsignedBigInteger("employe_id")->nullable()->index();
            $table->enum("payroll_method",["Tetap","Harian","Bulanan"])->default("Tetap");
            // Harian,Tetap Dan Bulanan        
            $table->integer("workday")->defaut(0);
            $table->float("percentage",4,2)->default(0.00);
            $table->float("amount",20,2)->default(0.00);
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
        Schema::dropIfExists('employe_payroll_parameters');
    }
}
