@extends('layout.main')
@section('page-title', 'Dashboard')
@section('page-subTitle', 'Dashboard IT Programer')
@section('content')

<div class="row">
    <div class="col">
        <form action="{{ route('admin.role.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="">Nama Role</label>
                <input type="text" name="jenis_user" class="form-control">
            </div>
            <button type="submit">Add</button>
        </form>
    </div>
</div>

@endsection
