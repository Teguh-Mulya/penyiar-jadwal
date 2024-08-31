@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title mb-4">Edit Jadwal Siaran</h1>
                
                <form action="{{ route('broadcasts.update', $broadcast) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="broadcast_name" class="form-label">Nama Siaran</label>
                        <input type="text" name="broadcast_name" id="broadcast_name" value="{{ old('broadcast_name', $broadcast->broadcast_name) }}" class="form-control" required>
                        @error('broadcast_name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $broadcast->description) }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" name="date" id="date" value="{{ old('date', $broadcast->date) }}" class="form-control" required>
                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="start_time" class="form-label">Waktu Mulai</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $broadcast->start_time) }}" class="form-control" required>
                        @error('start_time')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="end_time" class="form-label">Waktu Selesai</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $broadcast->end_time) }}" class="form-control" required>
                        @error('end_time')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
