<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GantiJadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'broadcast_id',
        'broadcaster_id',
        'submitter_id',
        'reason',
        'status'
    ];

    // Definisikan relasi dengan model Broadcast (jika ada)
    public function broadcast()
    {
        return $this->belongsTo(Broadcast::class, 'broadcast_id');
    }

    // Definisikan relasi dengan model User (jika broadcasters adalah pengguna)
    public function broadcaster()
    {
        return $this->belongsTo(User::class, 'broadcaster_id');
    }

    // Definisikan relasi dengan model User (jika broadcasters adalah pengguna)
    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }
}
