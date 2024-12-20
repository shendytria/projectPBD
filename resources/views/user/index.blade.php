@extends('layout.main')

@section('content')
<div class="container">
    <h2>Daftar User</h2>
    <a href="{{ url('/user/create') }}" class="btn btn-outline-primary">Tambah User</a>
    <table class="table table-striped mt-3">
        <thead>
            <tr class="table-dark">
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user['iduser'] }}</td>
                <td>{{ $user['username'] }}</td>
                <td>{{ $user['nama_role'] }}</td>
                <td>
                    <a href="{{ url('/user/' . $user['iduser'] . '/edit') }}" class="btn btn-outline-warning btn-sm">Edit</a>
                    <form action="{{ url('/user/' . $user['iduser']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?');" class="btn btn-outline-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
