@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title mb-4">Daftar Jadwal Siaran</h1>
                
                <a href="{{ route('broadcasts.create') }}" class="btn btn-primary mb-4">Tambah Jadwal Baru</a>
                
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
