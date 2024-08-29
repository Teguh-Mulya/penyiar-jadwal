<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastGuest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'broadcast_id', 'status'];

    public function radioBroadcast(): BelongsTo
    {
        return $this->belongsTo(RadioBroadcast::class, 'broadcast_id');
    }
}


