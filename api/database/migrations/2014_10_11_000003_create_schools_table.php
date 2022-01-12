<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->text("code")->nullable();
            $table->bigInteger("district_id")->unsigned()->nullable();
            $table->string("name",255)->nullable();
            $table->bigInteger("member")->default(0);
            $table->enum("level",["TK","SD","SMP","SMK","SMA","SLB"])->default("SD");
            $table->boolean("is_private")->default(1);
            $table->text("address")->nullable();
            $table->string("phone_headmaster",20)->nullable();
            $table->string("phone_teacher",20)->nullable();
            $table->string("phone_treasurer",20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("district_id")->references("id")->on("districts");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
