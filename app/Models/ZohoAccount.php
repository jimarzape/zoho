<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZohoAccount extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'client_secret', 'redirect_uri'];
}
