<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('msg')->nullable();
            $table->string('title')->nullable();
            $table->integer('uid')->default(0);
            $table->integer('sid')->default(0);
            $table->integer('booking_id')->default(0);
            $table->integer('schedule_id')->default(0);
            $table->integer('market_id')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->boolean('once')->default(0);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('notifications');
    }
}
