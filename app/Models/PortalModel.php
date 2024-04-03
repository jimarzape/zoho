<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProjectsModel;

class PortalModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'portal_id',
        'plan',
        'owner_id',
        'owner_email',
        'owner_name'
    ];

    public function projects()
    {
        return $this->belongsTo(ProjectsModel::class,'portal_id','id');
    }
}
