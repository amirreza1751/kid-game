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
            $table->string('Sid')->nullable();
            $table->string('Msisdn')->nullable();
            $table->string('TransId')->nullable();
            $table->string('Channel')->nullable();
            $table->string('ShortCode')->nullable();
            $table->string('KeyWord')->nullable();
            $table->string('TransStatus')->nullable();
            $table->string('DateTime')->nullable();
            $table->string('ChargeCode')->nullable();
            $table->string('BasePricePoint')->nullable();
            $table->string('BilledPricePoint')->nullable();
            $table->string('EventType')->nullable();
            $table->string('Status')->nullable();
            $table->string('Validity')->nullable();
            $table->string('NextRenewalDate')->nullable();
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
