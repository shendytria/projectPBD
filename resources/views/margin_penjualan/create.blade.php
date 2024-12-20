@extends('layout.main')

@section('content')
<div class="container">
    <h2>Form Margin Penjualan</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('margin_penjualan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="persen" class="form-label">Persen Margin</label>
            <input type="number" name="persen" id="persen" class="form-control" value="{{ old('persen') }}" placeholder="%" required>
            @error('persen')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Non-Aktif</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="iduser" class="form-label">Pengguna</label>
            <select name="iduser" id="iduser" class="form-select" required>
                <option value="">Pilih nama pengguna</option>
                @foreach ($users as $user)
                    <option value="{{ $user->iduser }}" {{ old('iduser') == $user->iduser ? 'selected' : '' }}>
                        {{ $user->username }}
                    </option>
                @endforeach
            </select>
            @error('iduser')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-outline-primary">Simpan Margin</button>
    </form>

    <hr>

    <h2>Daftar Margin Penjualan</h2>
    <div class="table-responsive">
    <table class="table table-striped">
    <thead>
        <tr class="table-dark">
            <th>No</th>
            <th>Persen Margin</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Dibuat Pada</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($margin_penjualan as $index => $margin)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $margin->persen }}%</td>
                <td>{{ $margin->username }}</td> <!-- Pastikan akses kolom ini -->
                <td>{{ $margin->status ? 'Aktif' : 'Non-Aktif' }}</td>
                <td>{{ $margin->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

    </div>
</div>
@endsection
