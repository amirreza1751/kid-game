<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('sid');
            $table->string('msisdn');
            $table->string('trans-id');
            $table->string('trans-status');
            $table->string('datetime');
            $table->string('channel');
            $table->string('shortcode');
            $table->string('keyword');
            $table->string('charge-code');
            $table->string('billed-price-point');
            $table->string('event-type');
            $table->string('validity');
            $table->string('next_renewal_date');
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
        Schema::dropIfExists('notifications');
    }
}
