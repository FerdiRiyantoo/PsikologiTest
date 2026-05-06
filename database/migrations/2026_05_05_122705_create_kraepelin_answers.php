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
        Schema::create('kraepelin_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_session_id');
            $table->foreign('test_session_id')
                ->references('id')->on('test_sessions')->onDelete('cascade');
            $table->integer('column_number');   // kolom ke-1 sampai 50
            $table->integer('row_number');      // baris ke-1 sampai 60
            $table->integer('digit_a');         // angka atas
            $table->integer('digit_b');         // angka bawah
            $table->integer('user_answer')->nullable(); // jawaban user (hasil penjumlahan)
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kraepelin_answers');
    }
};
