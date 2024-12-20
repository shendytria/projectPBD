@extends('layout.main')

@section('content')
<div class="container">
    <h2>Vendor</h2>

    <form action="{{ route('vendor.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="nama_vendor" class="form-label">Nama Vendor:</label>
            <input type="text" id="nama_vendor" name="nama_vendor" class="form-control" placeholder="Masukkan nama vendor" required>
        </div>

        <button type="submit" class="btn btn-outline-primary">Simpan</button>
        <a href="{{ route('vendor.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div>
@endsection
