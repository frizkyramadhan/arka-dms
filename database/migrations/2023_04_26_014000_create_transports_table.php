<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users'); // as courier
            $table->foreignId('delivery_id')->references('id')->on('deliveries');
            $table->dateTime('transport_date', 0);
            $table->string('transport_status'); // pending, on delivery, delivered, cancelled, returned
            $table->text('transport_remarks')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('transports');
    }
}
