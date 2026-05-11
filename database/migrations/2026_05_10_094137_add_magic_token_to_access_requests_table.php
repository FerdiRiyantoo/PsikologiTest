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
        Schema::table('access_requests', function (Blueprint $table) {
            $table->string('magic_token', 64)->nullable()->unique()->after('access_code');
            $table->timestamp('magic_token_expires_at')->nullable()->after('magic_token');
        });
    }

    public function down(): void
    {
        Schema::table('access_requests', function (Blueprint $table) {
            $table->dropColumn(['magic_token', 'magic_token_expires_at']);
        });
    }
};
