@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title mb-4">Tambah Jadwal Siaran Baru</h1>
                
                <form action="{{ route('broadcasts.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="broadcast_name" class="form-label">Nama Siaran</label>
                        <input type="text" name="broadcast_name" id="broadcast_name" value="{{ old('broadcast_name') }}" class="form-control" required>
                        @error('broadcast_name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
            
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
            
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" name="date" id="date" value="{{ old('date') }}" class="form-control" required>
                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
            
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Waktu Mulai</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="form-control" required>
                        @error('start_time')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
            
                    <div class="mb-3">
                        <label for="end_time" class="form-label">Waktu Selesai</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="form-control" required>
                        @error('end_time')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div> 

                    <div class="mb-3">
                        <label for="hosts" class="form-label">Penyiar</label>
                        <select class="js-example-basic-multiple form-control" name="hosts[]" id="hosts" multiple="multiple" required>
                            @foreach($hosts as $host)
                                <option value="{{ $host->id }}" {{ in_array($host->id, old('hosts', [])) ? 'selected' : '' }}>{{ $host->name }}</option>
                            @endforeach
                        </select>
                        @error('hosts')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
            
                     <!-- Input Dinamis untuk Menambah Host Bintang Tamu -->
                    <div class="mb-3">
                        <label class="form-label">Bintang Tamu</label>
                        <div id="guest-hosts-container">
                            <!-- Baris input untuk bintang tamu default -->
                            <div class="row mb-2" id="guest-host-0">
                                <div class="col-md-5">
                                    <input type="text" name="guest_hosts[0][name]" class="form-control" placeholder="Nama Bintang Tamu" required>
                                </div>
                                <div class="col-md-5">
                                    <select name="guest_hosts[0][status]" class="form-control" required>
                                        <option value="pending" selected>Pending</option>
                                        <option value="approved">Approved</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-guest-host" data-id="guest-host-0">Hapus</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" id="add-guest-host">Tambah Bintang Tamu</button>
                        @error('guest_hosts')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addGuestHostButton = document.getElementById('add-guest-host');
        const guestHostsContainer = document.getElementById('guest-hosts-container');
        let guestIndex = 1; // Mulai dari 1 karena sudah ada guest 0

        // Fungsi untuk menambahkan baris input dinamis
        addGuestHostButton.addEventListener('click', function() {
            const newGuestHost = document.createElement('div');
            newGuestHost.classList.add('row', 'mb-2');
            newGuestHost.setAttribute('id', `guest-host-${guestIndex}`);

            newGuestHost.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="guest_hosts[${guestIndex}][name]" class="form-control" placeholder="Nama Bintang Tamu" required>
                </div>
                <div class="col-md-5">
                    <select name="guest_hosts[${guestIndex}][status]" class="form-control" required>
                        <option value="pending" selected>Pending</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-guest-host" data-id="guest-host-${guestIndex}">Hapus</button>
                </div>
            `;

            // Tambahkan elemen baru ke dalam container
            guestHostsContainer.appendChild(newGuestHost);

            // Event listener untuk tombol hapus
            newGuestHost.querySelector('.remove-guest-host').addEventListener('click', function() {
                const guestId = this.getAttribute('data-id');
                const guestToRemove = document.getElementById(guestId);
                guestHostsContainer.removeChild(guestToRemove);
            });

            guestIndex++; // Increment untuk setiap guest yang ditambahkan
        });
    });
</script>
@endsection
