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
        Schema::create('zoho_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('client_id');
            $table->string('client_secret');
            $table->string('redirect_uri');
            $table->string('code')->nullable(true);
            $table->string('grant_type')->default('authorization_code');
            $table->string('access_token')->nullable(true);
            $table->string('refresh_token')->nullable(true);
            $table->string('access_type')->default('offline');
            $table->integer('expiry-time')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoho_accounts');
    }
};
