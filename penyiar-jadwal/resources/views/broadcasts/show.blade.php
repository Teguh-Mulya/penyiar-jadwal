@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="container mx-auto">
        <div class="card bg-white shadow-md rounded-lg">
            <div class="card-body p-6">
                <section class="mb-6">
                    <h1 class="text-2xl font-semibold mb-4">{{ $broadcast->broadcast_name }}</h1>
                    <div class="space-y-4">
                        <p><strong>Tanggal:</strong> {{ $broadcast->date->format('d-m-Y') }}</p>
                        <p><strong>Waktu Mulai:</strong> {{ $broadcast->start_time->format('H:i') }}</p>
                        <p><strong>Waktu Selesai:</strong> {{ $broadcast->end_time->format('H:i') }}</p>
                        <p><strong>Status:</strong> {{ $broadcast->status }}</p>
                        <p><strong>Deskripsi:</strong> {{ $broadcast->description }}</p>
                    </div>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Broadcast Hosts</h2>
                    @foreach ($broadcast->broadcastHosts as $host)
                        <div class="border border-gray-300 p-4 mb-4 rounded-lg">
                            <p><strong>User:</strong> {{ $host->user->name }}</p>
                            <p><strong>Description:</strong> {{ $host->description }}</p>
                        </div>
                    @endforeach
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Broadcast Guests</h2>
                    @foreach ($broadcast->broadcastGuests as $guest)
                        <div class="border border-gray-300 p-4 mb-4 rounded-lg">
                            <p><strong>Guest Name:</strong> {{ $guest->name }}</p>
                            <p><strong>Status:</strong> {{ $guest->status }}</p>
                        </div>
                    @endforeach
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Approval</h2>
                    
                    <div class="border border-gray-300 p-4 mb-4 rounded-lg">
                        <p><strong>Total Approval Koordinator Siaran:</strong> {{ $broadcast->koordinator_siaran_approved_count }} dari {{ $broadcast->koordinator_siaran_total_count }}</p>
                        <p><strong>Total Approval Kabid:</strong> {{ $broadcast->kabid_approved_count }} dari {{ $broadcast->kabid_total_count }}</p>
                        <p><strong>Total Approval Kepala Siaran:</strong> {{ $broadcast->kepala_siaran_approved_count }} dari {{ $broadcast->kepala_siaran_total_count }}</p>
                    </div>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Details
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Approval Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @foreach ($broadcast->approvals as $approval)
                                        <div class="border border-gray-300 p-4 mb-4 rounded-lg">
                                            <p><strong>Role:</strong> {{ $approval->role->role_name }}</p>
                                            <p><strong>Status:</strong> {{ $approval->status }}</p>
                                            <p><strong>Approver:</strong> {{ $approval->user->name }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Komentar</h2>
                    @foreach ($broadcast->comments as $comment)
                        <div class="border border-gray-300 p-4 mb-4 rounded-lg">
                            <p><strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Log Status</h2>
                    @foreach ($broadcast->logStatuses as $log)
                        <div class="border border-gray-300 p-4 mb-4 rounded-lg">
                            <p><strong>Status:</strong> {{ $log->status }}</p>
                            <p><strong>Description:</strong> {{ $log->description }}</p>
                        </div>
                    @endforeach
                </section>

                <section class="mb-6">
                    @php
                        $approvalConfig = $broadcast->approval_configuration;
                        $userRoles = Auth::user()->roles; // Ambil peran pengguna
                    @endphp
                    @foreach ($approvalConfig as $role => $config)
                        @if (Auth::user()->hasRole($config['role_name']) && $config['condition'])
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal_{{ $config['id'] }}">
                                {{ $config['button_text'] }}
                            </button>
                
                            <!-- Modal Bootstrap -->
                            <div class="modal fade" id="approveModal_{{ $config['id'] }}" tabindex="-1" aria-labelledby="approveModalLabel_{{ $role }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveModalLabel_{{ $role }}">Konfirmasi Persetujuan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('broadcasts.approve', $broadcast) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <p>Apakah Anda yakin ingin menyetujui jadwal ini?</p>
                
                                                @if ($userRoles->count() > 1) <!-- Cek jika pengguna memiliki lebih dari satu peran -->
                                                    <div class="mb-3">
                                                        <label for="approval_roles" class="form-label">Setujui sebagai:</label>
                                                        @foreach ($userRoles as $role)
                                                            @if ($role->id != 1)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="approval_roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}">
                                                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                                                        {{ $role->role_name }}
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Setujui Jadwal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </section>
                
            </div>
        </div>
    </div>
</div>
@endsection
