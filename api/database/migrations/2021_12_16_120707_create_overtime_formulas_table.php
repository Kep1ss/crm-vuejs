<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimeFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_formulas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("overtime_category_id")->unsigned()->nullable();
            $table->bigInteger("index_formula_id")->unsigned()->nullable();
            $table->text("formula")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('overtime_category_id')->references('id')->on('overtime_categories');
            $table->foreign('index_formula_id')->references('id')->on('index_formulas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_formulas');
    }
}
