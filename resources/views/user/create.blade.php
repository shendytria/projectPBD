@extends('layout.main')

@section('content')
<div class="container">
    <h2>User</h2>
    <form action="{{ url('/user') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" required>
        </div>
        <div class="form-group mb-3">
    <label for="idrole" class="form-label">Role</label>
    <select name="idrole" class="form-control" id="idrole" required>
        <option value="" disabled selected>Pilih nama role</option>
        @foreach ($roles as $role)
            <option value="{{ $role['idrole'] }}">{{ $role['nama_role'] }}</option>
        @endforeach
    </select>
</div>
        <div class="form-group mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-outline-primary">Simpan</button>
        <a href="{{ url('/user') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div>
@endsection
