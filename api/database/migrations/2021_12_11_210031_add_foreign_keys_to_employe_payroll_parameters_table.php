<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEmployePayrollParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employe_has_payroll_parameters', function (Blueprint $table) {
            $table->foreign(['employe_id'])->references(['id'])->on('employes');
            $table->foreign(['payroll_parameter_id'])->references(['id'])->on('payroll_parameters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employe_payroll_parameters', function (Blueprint $table) {
            $table->dropForeign('employe_payroll_parameters_employe_id_foreign');
            $table->dropForeign('employe_payroll_parameters_payroll_parameter_id_foreign');
        });
    }
}
