<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string("code",25)->nullable();
            $table->string("name",50)->nullable();
            $table->bigInteger("city_id")->unsigned()->nullable();
            $table->tinyInteger("done_level_smk_childs")->default(0);
            $table->tinyInteger("done_level_sma_childs")->default(0);
            $table->tinyInteger("done_level_smp_childs")->default(0);
            $table->tinyInteger("done_level_sd_childs")->default(0);
            $table->tinyInteger("done_level_tk_childs")->default(0);
            $table->tinyInteger("done_level_slb_childs")->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("city_id")->references("id")->on("cities");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disrtricts');
    }
}
