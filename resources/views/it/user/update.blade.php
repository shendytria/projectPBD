@extends('layout.main')
@section('page-title', 'Dashboard')
@section('page-subTitle', 'Dashboard IT Programer')
@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.user.update', $data->id) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="{{ $data->name }}">
                        </div>
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" class="form-control" name="username" value="{{ $data->username }}">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $data->email }}">
                        </div>
                        <div class="form-group">
                            <label for="">Role</label>
                            <select name="role" class="form-select">
                                <option value="">Pilih Role</option>
                                @foreach ($role as $item)
                                    <option value="{{ $item->id }}">{{ $item->jenis_user }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password" value="" placeholder="biarkan kosong jika tidak ingin mengubah">
                        </div>
                        <div class="form-group mt-3">
                            <a href="" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
