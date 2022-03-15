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
            $table->uuid('id')->primary();
            $table->foreignId('series_id')->nullable()->references('id')->on('series');
            $table->integer('receipt_no');
            $table->string('receipt_full_no')->nullable();
            $table->date('receipt_date');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('project_id')->nullable()->references('id')->on('projects');
            $table->string('to')->nullable();
            $table->string('attn')->nullable();
            $table->string('received_by')->nullable();
            $table->time('received_time')->nullable();
            $table->date('received_date')->nullable();
            $table->string('status')->nullable();
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
