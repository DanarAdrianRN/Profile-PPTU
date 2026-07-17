<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_visits', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('path')->nullable();
            $table->string('user_agent')->nullable();
            $table->date('visited_at')->index();
            $table->timestamps();

            $table->unique(['session_id', 'path', 'visited_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_visits');
    }
};
