<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastHost extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'broadcast_id', 'description'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function radioBroadcast(): BelongsTo
    {
        return $this->belongsTo(RadioBroadcast::class, 'broadcast_id');
    }
}


