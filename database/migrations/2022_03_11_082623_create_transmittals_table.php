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
            $table->integer('receipt_no');
            $table->string('receipt_full_no')->nullable();
            $table->date('receipt_date');
            $table->foreignId('user_id')->references('id')->on('users'); // as origin sender
            $table->foreignId('project_id')->nullable()->references('id')->on('projects');
            $table->foreignId('department_id')->nullable()->references('id')->on('departments');
            $table->foreignId('received_by')->nullable()->references('id')->on('users'); // as origin receiver
            $table->string('to')->nullable();
            $table->string('attn')->nullable();
            $table->enum('transmittal_status', ['published', 'on delivery', 'delivered', 'cancelled'])->default('published');
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
        Schema::dropIfExists('transmittals');
    }
}
