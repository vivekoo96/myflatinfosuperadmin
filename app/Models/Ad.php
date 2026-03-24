<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class Ad extends Model
{
    use HasFactory;
 protected $fillable = [
        'name', 'image', 'link', 'status', 'user_id', 'from_time', 'to_time',
        'notification_type', 'from_notified_at','is_notified'
    ];
    
    

    protected $casts = [
        'from_time' => 'datetime',
        'to_time' => 'datetime',
        'from_notified_at' => 'datetime',
          'is_notified' => 'boolean',
    ];

    public function getImageAttribute($value)
    {
        return asset('public/images/'.$value);
    }
    
    public function getImageFilenameAttribute()
    {
        return $this->attributes['image'] ?? null;
    }

    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'ad_buildings', 'ad_id', 'building_id')
            ->withTimestamps();
    }
}
