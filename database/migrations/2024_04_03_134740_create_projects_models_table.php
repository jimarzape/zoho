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
        Schema::create('projects_models', function (Blueprint $table) {
            $table->id();
            $table->biginteger('project_id')->unique();
            $table->unsignedBigInteger('portal_id');
            $table->foreign('portal_id')->references('id')->on('portal_models')->onDelete('cascade');
            $table->integer('owner_id');
            $table->string('name');
            $table->string('owner_email')->nullable();
            $table->string('owner_name')->nullable();
            $table->text('description')->nullable();
            $table->string('status');
            $table->date('end_date');
            $table->date('start_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_models');
    }
};
