<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastGuest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'broadcast_id', 'status', 'description'];

    public function radioBroadcast()
    {
        return $this->belongsTo(RadioBroadcast::class, 'broadcast_id');
    }
}
