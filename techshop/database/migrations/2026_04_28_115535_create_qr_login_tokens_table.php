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
        Schema::create('qr_login_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('expires_at');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_login_tokens');
    }
};
