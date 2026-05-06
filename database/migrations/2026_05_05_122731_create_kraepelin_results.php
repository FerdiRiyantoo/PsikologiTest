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
        Schema::create('kraepelin_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_session_id');
            $table->foreign('test_session_id')
                ->references('id')->on('test_sessions')->onDelete('cascade');
            $table->decimal('pace_score', 8, 2)->default(0);        // kecepatan
            $table->decimal('accuracy_score', 8, 2)->default(0);    // ketelitian %
            $table->decimal('endurance_score', 8, 2)->default(0);   // ketahanan
            $table->decimal('stability_score', 8, 2)->default(0);   // keajegan
            $table->integer('total_answered')->default(0);          // total dijawab
            $table->integer('total_correct')->default(0);           // total benar
            $table->json('raw_data')->nullable();                   // data mentah per kolom
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kraepelin_results');
    }
};
