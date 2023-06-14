<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->nullable();
            $table->integer('mid')->nullable();
            $table->string('items')->nullable();
            $table->string('item_image')->nullable();
            $table->string('category')->nullable();
            $table->string('booking_attribute')->nullable();
            $table->string('booking_price')->nullable();
            $table->string('note')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('items');
    }
}
