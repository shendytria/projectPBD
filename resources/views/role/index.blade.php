@extends('layout.main')

@section('content')
<div class="container">
    <h2>Daftar Role</h2>
    <a href="{{ route('roles.create') }}" class="btn btn-outline-primary">Tambah Role</a>

<!-- Tabel Daftar Role -->
<table class="table table-striped mt-3">
    <thead>
        <tr class="table-dark">
            <th>ID</th>
            <th>Nama Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($roles as $role)
        <tr>
            <td>{{ $role['idrole'] }}</td>
            <td>{{ $role['nama_role'] }}</td>
            <td>
                <a href="{{ route('roles.edit', $role['idrole']) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                <form action="{{ route('roles.destroy', $role['idrole']) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
