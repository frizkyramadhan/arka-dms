<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('transmittal_id')->references('id')->on('transmittals');
            $table->enum('delivery_type', ['send', 'receive']); // send or receive
            $table->dateTime('delivery_date', 0);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('unit_id')->nullable()->references('id')->on('units');
            $table->string('nopol')->nullable();
            $table->string('po_no')->nullable();
            $table->string('do_no')->nullable();
            $table->text('delivery_remarks')->nullable();
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
        Schema::dropIfExists('deliveries');
    }
}
