@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title mb-4">Daftar Jadwal Siaran</h1>
                
                <a href="{{ route('broadcasts.create') }}" class="btn btn-primary mb-4">Tambah Jadwal Baru</a>
                <a target="_blank" href="{{ route('broadcasts.print.pdf') }}" class="btn btn-info mb-4">Cetak Jadwal</a>
                @if ($broadcasts->isEmpty())
                    <p>Tidak ada jadwal siaran yang tersedia.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Siaran</th>
                                <th>Tanggal</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                                <th>Ganti Jadwal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($broadcasts as $broadcast)
                                <tr>
                                    <td>{{ $broadcast->broadcast_name }}</td>
                                    <td>{{ $broadcast->date->format('d-m-Y') }}</td>
                                    <td>{{ $broadcast->start_time->format('H:i') }}</td>
                                    <td>{{ $broadcast->end_time->format('H:i') }}</td>
                                    <td>{{ $broadcast->status }}</td>
                                    <td>
                                        <a href="{{ route('broadcasts.show', $broadcast) }}" class="btn btn-info btn-sm">Lihat</a>
                                        <a href="{{ route('broadcasts.edit', $broadcast) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('broadcasts.destroy', $broadcast) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                    <td>
                                        <!-- @if (!$broadcast->broadcastHosts()->where('user_id', auth()->user()->id)->exists() && !$broadcast->gantiJadwals()->where('submitter_id', auth()->user()->id)->whereIn('status',['rejected','submit'])->exists())
                                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal">Ganti Jadwal Siaran</a>
                                        @else
                                            {{$broadcast->gantiJadwals()->where('submitter_id', auth()->user()->id)->whereIn('status',['rejected','submit'])->latest()->first()?->status}}
                                            @endif -->
                                        @if ($broadcast->broadcastHosts()->where('user_id', auth()->user()->id)->exists() && !$broadcast->gantiJadwals()->where('submitter_id', auth()->user()->id)->whereIn('status',['rejected','submit'])->exists())
                                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal">Ganti Jadwal Siaran</a>
                                        @endif

                                        @if($koor && ($broadcast->gantiJadwals()->count() > 0))
                                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#checkScheduleModal">Lihat</a>
                                        @endif
                                    </td>

                                    <!-- Modal Bootstrap -->
                                    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="scheduleModalLabel">Ganti Jadwal Siaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="scheduleForm" action="{{ route('broadcasts.gantijadwal', $broadcast) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="broadcasterSelect" class="form-label">Pilih Penyiar</label>
                                                            <select class="form-select" id="broadcasterSelect" name="broadcaster_id" required>
                                                                <option value="" disabled selected>Pilih penyiar...</option>
                                                                @foreach ($hosts as $host)
                                                                    <option value="{{ $host->id }}">{{ $host->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="reasonTextarea" class="form-label">Alasan Ganti Jadwal</label>
                                                            <textarea class="form-control" id="reasonTextarea" name="reason" rows="3" required></textarea>
                                                        </div>
                                                        <input type="hidden" name="broadcast_id" value="{{ $broadcast->id }}">
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" form="scheduleForm">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="checkScheduleModal" tabindex="-1" aria-labelledby="checkScheduleModal" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="checkScheduleModal">Ganti Jadwal Siaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                <div class="row mb-4">
        <div class="col">
            <h2>Broadcast List</h2>
        </div>
    </div>
    <div class="row">
        @foreach ($broadcast->gantiJadwals as $gantiJadwal)
            <div class="col-12 mb-3 border p-3">
                <div class="row">
                    <div class="col">
                        <strong>Submitter:</strong> {{ $gantiJadwal->submitter->name }}
                    </div>
                    <div class="col">
                        <strong>Broadcaster:</strong> {{ $gantiJadwal->broadcaster->name }}
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary">Approve</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
