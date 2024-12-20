@extends('layout.main')

@section('content')
<div class="container">
    <h2>Edit User</h2>
    <form action="{{ url('/user/' . $user['iduser']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" value="{{ $user['username'] }}" required>
        </div>
        <div class="form-group">
            <label for="idrole" class="form-label">Role</label>
            <select name="idrole" class="form-control" id="idrole" required>
                @foreach ($roles as $role)
                    <option value="{{ $role['idrole'] }}" {{ $user['idrole'] == $role['idrole'] ? 'selected' : '' }}>
                        {{ $role['nama_role'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-outline-primary mt-3">Update</button>
    </form>
</div>
@endsection
