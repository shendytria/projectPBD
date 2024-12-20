@extends('layout.main')

@section('content')
<div class="container">
    <h2>Role</h2>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <!-- {{-- <div class="form-group mb-3">
            <label for="idrole">ID Role</label>
            <input type="number" name="idrole" id="idrole" class="form-control" required>
        </div> --}} -->
        <div class="form-group mb-3">
            <label for="nama_role" class="form-label">Nama Role</label>
            <input type="text" name="nama_role" id="nama_role" class="form-control" placeholder="Masukkan nama role" required>
        </div>
        <button type="submit" class="btn btn-outline-primary">Simpan</button>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div>
@endsection
