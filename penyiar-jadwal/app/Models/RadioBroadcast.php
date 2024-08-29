<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioBroadcast extends Model
{
    use HasFactory;

    protected $fillable = ['broadcast_name', 'description', 'date', 'start_time', 'end_time', 'status'];

    public function broadcastHosts()
    {
        return $this->hasMany(BroadcastHost::class);
    }

    public function broadcastGuests()
    {
        return $this->hasMany(BroadcastGuest::class);
    }
}
