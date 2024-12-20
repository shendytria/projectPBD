@extends('layout.main')
@section('page-title', 'Dashboard')
@section('page-subTitle', 'Dashboard IT Programer')
@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.role.update', $data->id) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <!-- Name Field -->
                                <div class="form-group">
                                    <label for="name">Nama Jenis User</label>
                                    <input type="text" name="jenis_user" id="jenis_user" class="form-control"
                                        value="{{ old('name', $data->jenis_user) }}" required>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update User</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
