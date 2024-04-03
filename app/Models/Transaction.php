<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'internal_hours_approver',
        'customer_hours_approver',
        'project_name',
        'total_billable_hours',
        'total_non_billable_hours',
        'account_name',
        'account_id',
        'project_description',
        'monthly_hours_budget',
        'reporting_period_start',
        'reporting_period_end',
        'project_id',
        'portal_id'
    ];
}
