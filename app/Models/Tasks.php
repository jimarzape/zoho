<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'project_id',
        'portal_id',
        'name',
        'end_date',
        'start_date',
        'description',
        'owner_id',
        'owner_name',
        'owner_email',
        'non_billable_hours',
        'billable_hours',
        'duration',
        'duration_type',
        'customer_hours_approver',
        'monthly_hour_budget'
    ];
}
