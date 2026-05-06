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
        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_session_id'); // ← harus unsignedBigInteger
            $table->foreign('test_session_id')
                ->references('id')
                ->on('test_sessions')
                ->onDelete('cascade');
            $table->integer('question_number');
            $table->string('chosen_option');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_answers');
    }
};
