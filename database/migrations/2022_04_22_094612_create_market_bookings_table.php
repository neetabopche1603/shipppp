<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('mid')->default(0);
            $table->integer('receptionist_id')->default(0);
            $table->integer('pickupagent_id')->default(0);
            $table->integer('departure_id')->default(0);
            $table->integer('arrival_id')->default(0);
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
        Schema::dropIfExists('market_bookings');
    }
}
