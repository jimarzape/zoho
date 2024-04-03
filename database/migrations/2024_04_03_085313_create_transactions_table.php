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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('internal_hours_approver');
            $table->string('customer_hours_approver')->nullable();
            $table->string('project_name');
            $table->float('total_billable_hours');
            $table->float('total_non_billable_hours');
            $table->string('account_name');
            $table->unsignedBigInteger('account_id');
            $table->text('project_description')->nullable();
            $table->float('monthly_hours_budget')->nullable();
            $table->date('reporting_period_start');
            $table->date('reporting_period_end');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
