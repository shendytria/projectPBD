@extends('layout.main')

@section('content')
<div class="container">
    <h2>Edit Role</h2>
        <div class="card-body">
            <form action="{{ route('roles.update', $role['idrole']) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Input form with Bootstrap styling -->
                <div class="form-group mb-3">
                    <label for="nama_role" class="form-label">Nama Role</label>
                    <input type="text" id="nama_role" name="nama_role" class="form-control" value="{{ $role['nama_role'] }}" required>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-outline-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
