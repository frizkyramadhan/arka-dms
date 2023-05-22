<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->references('id')->on('deliveries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade'); // as courier
            // $table->foreignId('unit_id')->nullable()->references('id')->on('units');
            // $table->string('nopol')->nullable();
            $table->enum('transport_status', ['pending', 'on delivery', 'delivered', 'cancelled', 'returned'])->default('pending');
            $table->dateTime('transport_date', 0)->nullable();
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
        Schema::dropIfExists('delivery_orders');
    }
}
