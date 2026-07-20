<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ClassifiedPhoto extends Model
{
    use HasFactory;
    
    public function classified()
    {
        return $this->belongsTo('App\Models\Classified')->withTrashed();
    }
    
    public function getPhotoAttribute($value)
    {
        if ($value != '') {
            $filename = basename($value);
            
            return asset('public/images/classifieds/' . $filename);
        }
    }
    
    public function getPhotoFilenameAttribute()
    {
        return $this->attributes['photo'] ?? null;
    }
    
}
