<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogStatus extends Model
{
    use HasFactory;

    protected $fillable = ['radio_broadcast_id', 'user_id', 'status', 'description'];

    public function radioBroadcast(): BelongsTo
    {
        return $this->belongsTo(RadioBroadcast::class, 'radio_broadcast_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

