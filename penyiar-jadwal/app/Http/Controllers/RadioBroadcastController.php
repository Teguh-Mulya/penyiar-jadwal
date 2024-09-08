<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RadioBroadcast;
use App\Models\Approval;
use App\Models\LogStatus;
use App\Models\Comment;
use App\Models\Role;
use App\Models\User;
use App\Models\GantiJadwal;
use PDF;

class RadioBroadcastController extends Controller
{
    public function index()
    {
        $broadcasts = RadioBroadcast::with(['logStatuses', 'comments', 'approvals', 'gantiJadwals'])->get();
        return view('broadcasts.index', compact('broadcasts'));
    }

    public function create()
    {
        return view('broadcasts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'broadcast_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        $broadcast = RadioBroadcast::create($request->all());

        // Create initial log status
        LogStatus::create([
            'radio_broadcast_id' => $broadcast->id,
            'user_id' => auth()->id(), // Assuming the current user creates the broadcast
            'status' => 'created',
            'description' => 'Broadcast created.',
        ]);

        // Create approval entries for required roles
        $roles = Role::whereIn('role_name', ['Koordinator Siaran', 'Kepala Bidang Siaran', 'Kepala Stasiun'])->get();
        $users = User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('role_id', $roles->pluck('id'));
        })->get();

        foreach ($users as $user) {
            $roleIds = $user->roles->pluck('id')->toArray();

            $approval = Approval::create([
                'radio_broadcast_id' => $broadcast->id,
                'user_id' => $user->id,
                'status' => 'pending',
            ]);

            $approval->roles()->sync($roleIds);
        }

        return redirect()->route('broadcasts.index')->with('success', 'Broadcast created successfully.');
    }

    public function show(RadioBroadcast $broadcast)
    {
        $broadcast->load(['logStatuses', 'comments', 'approvals']);
        return view('broadcasts.show', compact('broadcast'));
    }

    public function edit(RadioBroadcast $broadcast)
    {
        return view('broadcasts.edit', compact('broadcast'));
    }

    public function update(Request $request, RadioBroadcast $broadcast)
    {
        $request->validate([
            'broadcast_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        $broadcast->update($request->all());

        // Update log status
        LogStatus::create([
            'radio_broadcast_id' => $broadcast->id,
            'user_id' => auth()->id(), // Assuming the current user updates the broadcast
            'status' => 'updated',
            'description' => 'Broadcast updated.',
        ]);

        return redirect()->route('broadcasts.index')->with('success', 'Broadcast updated successfully.');
    }

    public function destroy(RadioBroadcast $broadcast)
    {
        $broadcast->delete();

        // Create log status for deletion
        LogStatus::create([
            'radio_broadcast_id' => $broadcast->id,
            'user_id' => auth()->id(), // Assuming the current user deletes the broadcast
            'status' => 'deleted',
            'description' => 'Broadcast deleted.',
        ]);

        return redirect()->route('broadcasts.index')->with('success', 'Broadcast deleted successfully.');
    }

    public function addComment(Request $request, RadioBroadcast $broadcast)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        Comment::create([
            'radio_broadcast_id' => $broadcast->id,
            'user_id' => auth()->id(), // Assuming the current user adds the comment
            'comment' => $request->comment,
        ]);

        return redirect()->route('broadcasts.show', $broadcast->id)->with('success', 'Comment added successfully.');
    }

    public function approve(Request $request, RadioBroadcast $broadcast)
    {
        $userRoles = auth()->user()->roles;
        $rules = [
            'status' => 'required|in:approved,rejected',
        ];

        if ($userRoles->count() > 1) {
            // Tambahkan validasi required untuk approval_roles jika pengguna memiliki lebih dari satu peran
            $rules['approval_roles'] = 'required|array|min:1';
        }

        $request->validate($rules);

        if ($request->approval_roles && is_array($request->approval_roles)) {
            // Jika ada approve_roles, lakukan update massal
            foreach ($request->approval_roles as $key => $approval_roles) {
                Approval::where([
                        'radio_broadcast_id' => $broadcast->id,
                        'user_id' => auth()->id(),
                        'role_id' => $approval_roles
                    ])
                    ->update(['status' => $request->status]);
            }
        } else {
            // Jika tidak ada approve_roles, update satu persetujuan
            $approval = Approval::where([
                'radio_broadcast_id' => $broadcast->id,
                'user_id' => auth()->id(),
            ])->first();
        
            if ($approval) {
                $approval->status = $request->status;
                $approval->save();
            }
        }

        // Check if all approvals are 'approved'
        $allApproved = Approval::where('radio_broadcast_id', $broadcast->id)
            ->where('status', '!=', 'approved')
            ->doesntExist(); // Checks if no other statuses exist except 'approved'

        if ($allApproved) {
            // Update broadcast status to 'approved'
            $broadcast->status = 'approved';
            $broadcast->save();

            // Log the broadcast status change
            LogStatus::create([
                'radio_broadcast_id' => $broadcast->id,
                'user_id' => auth()->id(), // Or use a specific user for this log if needed
                'status' => 'approved',
                'description' => 'Broadcast status updated to approved.',
            ]);
        }

        return redirect()->route('broadcasts.show', $broadcast->id)->with('success', 'Broadcast approval status updated.');
    }

    // export PDF
    public function exportPDF() 
    {
        // Mendapatkan tanggal hari ini
        $today = \Carbon\Carbon::today();
        
        // Mengambil hanya siaran yang terjadi pada hari ini
        $data = RadioBroadcast::whereDate('date', $today)->get();
    
        // Memuat tampilan dengan data yang sudah difilter
        $pdf = PDF::loadView('broadcasts.pdf', ['data' => $data]);
            
        // Menampilkan PDF yang sudah dihasilkan
        return $pdf->stream('jadwal.pdf');
    }

    public function gantiJadwal(Request $request, RadioBroadcast $broadcast)
    {
        // Validasi data input
        $validated = $request->validate([
            'broadcaster_id' => 'required|exists:users,id', // Asumsikan penyiar adalah pengguna
            'reason' => 'required|string|max:255',
        ]);

        // Menambahkan ID broadcast ke data yang divalidasi
        $validated['broadcast_id'] = $broadcast->id;
        $validated['status'] = 'submit';
        $validated['submitter_id'] = auth()->user()->id;

        // Simpan data ke tabel ganti_jadwal
        GantiJadwal::create($validated);

        // Log the broadcast status change
        LogStatus::create([
            'radio_broadcast_id' => $broadcast->id,
            'user_id' => auth()->id(), // Or use a specific user for this log if needed
            'status' => 'submit',
            'description' => 'permintaan ganti jadwal',
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('broadcasts.index')->with('success', 'Jadwal siaran berhasil diganti.');
    }
}
