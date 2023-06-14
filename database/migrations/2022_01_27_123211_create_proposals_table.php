<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->nullable();
            $table->integer('sid')->nullable();
            $table->integer('mid')->nullable();
            $tabel->string('proposals')->nullable();
            $tabel->string('type')->nullable();
            $tabel->string('pickupfee')->nullable();
            $tabel->string('shipping_price')->nullable();
            $tabel->string('tax')->nullable();
            $tabel->string('status')->default(0);
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
        Schema::dropIfExists('proposals');
    }
}
