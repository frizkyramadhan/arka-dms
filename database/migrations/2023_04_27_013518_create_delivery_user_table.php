<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->references('id')->on('deliveries');
            $table->foreignId('user_id')->references('id')->on('users'); // as courier
            // $table->foreignId('unit_id')->nullable()->references('id')->on('units');
            // $table->string('nopol')->nullable();
            $table->dateTime('transport_date', 0)->nullable();
            $table->string('transport_status')->nullable(); // pending, on delivery, delivered, cancelled, returned
            $table->text('transport_remarks')->nullable();
            $table->string('transport_image')->nullable();
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
        Schema::dropIfExists('delivery_user');
    }
}
