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
        Schema::create('portal_models', function (Blueprint $table) {
            $table->id();
            $table->integer('portal_id')->unique();
            $table->string('plan');
            $table->integer('owner_id');
            $table->string('owner_email')->nullable();
            $table->string('owner_name')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portal_models');
    }
};
