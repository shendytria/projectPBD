@extends('layout.main')

@section('content')
<div class="container">
    <h2>Edit Vendor</h2>

    <form action="{{ route('vendor.update', $vendor['idvendor']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_vendor" class="form-label">Nama Vendor:</label>
            <input type="text" id="nama_vendor" name="nama_vendor" class="form-control" value="{{ $vendor['nama_vendor'] }}" required>
        </div>

        <button type="submit" class="btn btn-outline-primary mt-3">Update</button>
    </form>
</div>
@endsection
