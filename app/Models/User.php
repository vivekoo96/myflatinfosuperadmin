<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $hidden = [
        'password', 'remember_token','api_token','device_token','otp',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function sent_notifications()
    {
        return $this->hasMany('App\Models\Notification','from_id');
    }
    
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function getPhotoAttribute($value)
    {
        if($value != ''){
            // return Cache::remember("signed_url_{$value}", now()->addMinutes(10), function () use ($value) {
            //     return Storage::disk('s3')->temporaryUrl($value, now()->addMinutes(10)); // Expires in 10 min
            // });
            $file_path = public_path('images/' . $this->photo_filename);
            if (is_file($file_path)) {
                    return asset('public/images/'.$value);
            }else{
                return 'https://dev.buildingadmin.myflatinfo.com/public/images/'.$value;
            }
        }else{
            if($this->gender == 'Male'){
                return asset('public/images/profiles/male.png');
            }
            if($this->gender == 'Female' && $value == ''){
                return asset('public/images/profiles/female.jpeg');
            }
        }
    }
    
    public function buildings()
    {
        return $this->hasMany('App\Models\Building');
    }
    
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
    
    public function getPhotoFilenameAttribute()
    {
        return $this->attributes['photo'];
    }
    
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
    
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }
    
    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }
    
    public function mandal()
    {
        return $this->belongsTo('App\Models\Mandal');
    }
    
    private function generateUniqueString($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'user_permissions');
    }
    
    public function hasPermission($slug)
    {
        if($this->role == 'super-admin'){
            return true;
        }
        return $this->permissions()->where('slug', $slug)->exists();
    }
}
