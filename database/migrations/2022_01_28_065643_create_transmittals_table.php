<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransmittalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transmittals', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no');
            $table->date('receipt_date');
            $table->foreignId('project_id')->references('id')->on('projects');
            $table->string('to');
            $table->string('attn');
            $table->string('received_by');
            $table->time('received_time');
            $table->date('received_date');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transmittals');
    }
}
