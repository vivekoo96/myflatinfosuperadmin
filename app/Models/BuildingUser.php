<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingUser extends Model
{
    use HasFactory,SoftDeletes;
    
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
    
    public function building()
    {
        return $this->belongsTo('App\Models\Building')->withTrashed();
    }
    
}
