<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username',100)->unique();
            $table->string("fullname",100)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token',100)->nullable();			
            $table->decimal("target_copies",20,2)->default(0)->usigned();
            $table->integer("role")->default(0);
            /*
                // ROLE =>
                0 => Super Admin
                1 => Manager Nasional
                2 => Manager Area
                3 => Kaper 
                4 => SPV
                5 => Sales
                6 => Kotele
                7 => Tele Marketing
                8 => Admin Nasional
                9 => Admin Area
                10 => Admin Kaper
            */
            $table->bigInteger("parent_id")->unsigned()->nullable();
            $table->bigInteger("province_id")->unsigned()->nullable();
            $table->bigInteger("city_id")->unsigned()->nullable();
            $table->bigInteger("district_id")->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("province_id")->references("id")->on("provinces");
            $table->foreign("city_id")->references("id")->on("cities");
            $table->foreign("district_id")->references("id")->on("districts");
            $table->foreign('parent_id')->references('id')->on('users');         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
