<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string("code",25)->nullable();
            $table->string("name",50)->nullable();
            $table->unsignedBigInteger("province_id")->nullable();
            $table->tinyInteger("is_city")->default(0);
            $table->tinyInteger("done_childs")->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("province_id")->references("id")->on("provinces");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
