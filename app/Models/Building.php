<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class Building extends Model
{
    use HasFactory, SoftDeletes;
    
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function users()
    {
        return $this->hasMany('App\Models\BuildingUser')->withTrashed();
    }

    public function builder()
    {
        return $this->belongsTo('App\Models\Builder')->withTrashed();
    }
    
    public function flats()
    {
        return $this->hasMany('App\Models\Flat')->withTrashed();
    }

    public function blocks()
    {
        return $this->hasMany('App\Models\Block')->withTrashed();
    }
    
    public function city()
    {
        return $this->belongsTo('App\Models\City')->withTrashed();
    }
    
    public function getImageAttribute($value)
    {
        if($value != ''){
            return Cache::remember("signed_url_{$value}", now()->addMinutes(10), function () use ($value) {
                return Storage::disk('s3')->temporaryUrl($value, now()->addMinutes(10)); // Expires in 10 min
            });
        }
        $setting = Setting::first();
        return $setting->logo;
    }

    public function getImageFilenameAttribute()
    {
        return $this->attributes['image'] ?? null;
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'building_permissions');
    }

    
}
