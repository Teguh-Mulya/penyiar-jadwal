<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RadioBroadcast extends Model
{
    use HasFactory;

    protected $fillable = ['broadcast_name', 'description', 'date', 'start_time', 'end_time', 'status'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

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
    }public function getApprovedCountByRole(string $roleName): int
    {
        return $this->approvals()
            ->whereHas('roles', function($query) use ($roleName) {
                $query->where('role_name', $roleName);
            })
            ->where('status', 'approved')
            ->count();
    }
    
    public function getTotalCountByRole(string $roleName): int
    {
        return $this->approvals()
            ->whereHas('roles', function($query) use ($roleName) {
                $query->where('role_name', $roleName);
            })
            ->count();
    }
    
    // Accessor methods
    public function getKabidApprovedCountAttribute()
    {
        return $this->getApprovedCountByRole('Kabid');
    }
    
    public function getKabidTotalCountAttribute()
    {
        return $this->getTotalCountByRole('Kabid');
    }
    
    public function getKoordinatorSiaranApprovedCountAttribute()
    {
        return $this->getApprovedCountByRole('Koordinator Siaran');
    }
    
    public function getKoordinatorSiaranTotalCountAttribute()
    {
        return $this->getTotalCountByRole('Koordinator Siaran');
    }
    
    public function getKepalaSiaranApprovedCountAttribute()
    {
        return $this->getApprovedCountByRole('Kepala Siaran');
    }
    
    public function getKepalaSiaranTotalCountAttribute()
    {
        return $this->getTotalCountByRole('Kepala Siaran');
    }    

    public function getIsApprovedAttribute()
    {
        return $this->approvals->where('user_id', auth()->user()->id)->where('status', 'pending')->count() > 0;
    }

    public function getApprovalConfigurationAttribute()
    {
        return [
            'Koordinator Siaran' => [
                'id' => '1',
                'condition' => $this->is_approved,
                'role_id' => auth()->user()->roles()->first()->id,
                'button_text' => 'Setujui Jadwal',
                'role_name' => 'Koordinator Siaran'
            ],
            'Kabid' => [
                'id' => '2',
                'condition' => $this->is_approved && $this->koordinator_siaran_approved_count == $this->koordinator_siaran_total_count,
                'role_id' => auth()->user()->roles()->first()->id,
                'button_text' => 'Setujui Jadwal',
                'role_name' => 'Kabid'
            ],
            'Kepala Siaran' => [
                'id' => '3',
                'condition' => $this->is_approved && $this->kabid_approved_count == $this->kabid_total_count,
                'role_id' => auth()->user()->roles()->first()->id,
                'button_text' => 'Setujui Jadwal',
                'role_name' => 'Kepala Siaran'
            ]
        ];
    }

}



