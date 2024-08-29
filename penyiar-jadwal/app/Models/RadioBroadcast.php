<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RadioBroadcast extends Model
{
    use HasFactory;

    protected $fillable = ['broadcast_name', 'description', 'date', 'start_time', 'end_time', 'status'];

    public function broadcastHosts(): HasMany
    {
        return $this->hasMany(BroadcastHost::class, 'broadcast_id');
    }

    public function broadcastGuests(): HasMany
    {
        return $this->hasMany(BroadcastGuest::class, 'broadcast_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'radio_broadcast_id');
    }

    public function logStatuses(): HasMany
    {
        return $this->hasMany(LogStatus::class, 'radio_broadcast_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'radio_broadcast_id');
    }
}



