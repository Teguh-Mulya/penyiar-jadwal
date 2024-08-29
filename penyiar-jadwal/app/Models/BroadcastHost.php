<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastHost extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'broadcast_id', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function radioBroadcast()
    {
        return $this->belongsTo(RadioBroadcast::class, 'broadcast_id');
    }
}
