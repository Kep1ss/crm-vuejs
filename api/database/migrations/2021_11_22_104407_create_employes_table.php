<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            #  nik/nip
            $table->string('code', 16)->unique();
            # citizen_id
            $table->string('citizen_id', 16);
            $table->string('name', 200);
            $table->enum('gender', ['L', 'P'])->nullable()->default('L');
            $table->string('address', 200)->nullable();
            $table->string('city', 25)->nullable();
            $table->date('birth_day')->nullable();
            # academic
            $table->string('graduate', 30)->nullable();
            $table->string('phone', 18)->nullable();
            $table->unsignedBigInteger('division_id')->nullable()->index();
            $table->unsignedBigInteger('position_id')->nullable()->index();
            $table->date('join_date')->nullable();
            # re_signed
            $table->date('resigned')->nullable();
            $table->string('npwp', 20)->nullable();
            $table->decimal("salary",20,2)->default(0);
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
        Schema::dropIfExists('employes');
    }
}
