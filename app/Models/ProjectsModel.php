<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PortalModel;

class ProjectsModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'portal_id',
        'name',
        'owner_id',
        'owner_email',
        'owner_name',
        'description',
        'status',
        'end_date',
        'start_date',
    ];

    public function portals()
    {
        return $this->hasOne(PortalModel::class,'id','portal_id');
    }
}
