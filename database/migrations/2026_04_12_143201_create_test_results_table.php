<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_session_id'); // ← harus unsignedBigInteger
            $table->foreign('test_session_id')
                ->references('id')
                ->on('test_sessions')
                ->onDelete('cascade');
            $table->integer('scale_n')->default(0);
            $table->integer('scale_g')->default(0);
            $table->integer('scale_a')->default(0);
            $table->integer('scale_l')->default(0);
            $table->integer('scale_p')->default(0);
            $table->integer('scale_i')->default(0);
            $table->integer('scale_t')->default(0);
            $table->integer('scale_v')->default(0);
            $table->integer('scale_x')->default(0);
            $table->integer('scale_s')->default(0);
            $table->integer('scale_b')->default(0);
            $table->integer('scale_o')->default(0);
            $table->integer('scale_r')->default(0);
            $table->integer('scale_d')->default(0);
            $table->integer('scale_c')->default(0);
            $table->integer('scale_z')->default(0);
            $table->integer('scale_e')->default(0);
            $table->integer('scale_k')->default(0);
            $table->integer('scale_f')->default(0);
            $table->integer('scale_w')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
