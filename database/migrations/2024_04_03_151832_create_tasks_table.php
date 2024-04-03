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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects_models')->onDelete('cascade');
            $table->unsignedBigInteger('portal_id');
            $table->foreign('portal_id')->references('id')->on('portal_models')->onDelete('cascade');
            $table->biginteger('task_id');
            $table->string('name');
            $table->date('end_date');
            $table->date('start_date');
            $table->text('description')->nullable();
            $table->integer('owner_id');
            $table->string('owner_name');
            $table->string('owner_email');
            $table->float('non_billable_hours')->default(0);
            $table->float('billable_hours')->default(0);
            $table->integer('duration')->default(0)->nullable();
            $table->string('duration_type')->nullable();
            $table->string('customer_hours_approver')->nullable();
            $table->float('monthly_hour_budget')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
