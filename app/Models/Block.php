<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class Block extends Model
{
    use HasFactory, SoftDeletes;
    
    public function building()
    {
        return $this->belongsTo('App/Models/Building');
    }

    public function flats()
    {
        return $this->hasMany('App/Models/Flat');
    }
    
    
}
