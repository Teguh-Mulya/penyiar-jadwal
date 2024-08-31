<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RadioBroadcast;
use App\Models\Approval;
use App\Models\LogStatus;
use App\Models\Comment;
use App\Models\Role;
use App\Models\User;

class RadioBroadcastController extends Controller
{
    public function index()
    {
        $broadcasts = RadioBroadcast::with(['logStatuses', 'comments', 'approvals'])->get();
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
        $roles = Role::whereIn('role_name', ['Koordinator Siaran', 'Kabid', 'Kepala Siaran'])->get();
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
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $approval = Approval::where([
            'radio_broadcast_id' => $broadcast->id,
            'user_id' => auth()->id(), // Assuming the current user is making the approval
        ])->first();

        if ($approval) {
            $approval->status = $request->status;
            $approval->save();

            // Create log status for approval
            LogStatus::create([
                'radio_broadcast_id' => $broadcast->id,
                'user_id' => auth()->id(),
                'status' => $request->status,
                'description' => 'Approval status updated.',
            ]);

            return redirect()->route('broadcasts.show', $broadcast->id)->with('success', 'Broadcast approval status updated.');
        }

        return redirect()->route('broadcasts.show', $broadcast->id)->with('error', 'Approval not found.');
    }
}
