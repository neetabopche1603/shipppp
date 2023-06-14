<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->string('booking_attribute');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->string('delivery_days');
            $table->string('items');
            $table->string('booking_price');
            $table->string('dropoff');
            $table->string('item_image');
            $table->string('description');
            $table->string('needs');
            $table->string('status');
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
        Schema::dropIfExists('marketps');
    }
}
