<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_alt')->nullable();
            $table->string('phone');
            $table->string('phone_alt')->nullable();
            $table->string('car_info')->nullable();
            $table->date('storage_time')->nullable();
            $table->string('price')->nullable();
            $table->integer('paid')->default(0);
            $table->string('descr_category')->nullable();
            $table->string('descr_name')->nullable();
            $table->string('descr_notise')->nullable();
            $table->string('descr_amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storages');
    }
}
