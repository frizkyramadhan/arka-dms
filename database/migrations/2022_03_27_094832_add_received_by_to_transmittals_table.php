<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceivedByToTransmittalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transmittals', function (Blueprint $table) {
            $table->unsignedBigInteger('received_by')->nullable()->after('attn');
            $table->foreign('received_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transmittals', function (Blueprint $table) {
            $table->dropForeign(['received_by']);
            $table->dropColumn('received_by');
        });
    }
}
