<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'from_id', 'title', 'body', 'dataPayload', 'read_at', 'admin_read'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
    
    public function from_user()
    {
        return $this->belongsTo('App\Models\User','from_id')->withTrashed();
    }
}
