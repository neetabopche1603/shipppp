<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_type');
            $table->string('from');
            $table->string('to');
            $table->string('departure_date');
            $table->string('destination_warehouse');
            $table->longtext('item_type');
            $table->string('shipping_fee');
            $table->string('pickup_fee');
            $table->string('permission_status');
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
        Schema::dropIfExists('schedules');
    }
}
