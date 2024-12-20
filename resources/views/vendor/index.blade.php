@extends('layout.main')

@section('content')
<div class="container">
    <h2>Daftar Vendor</h2>
    <a href="{{ route('vendor.create') }}" class="btn btn-outline-primary">Tambah Vendor</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped mt-3">
        <thead>
            <tr class="table-dark">
                <th>ID</th>
                <th>Nama Vendor</th>
                <th>Badan Hukum</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vendors as $vendor)
                <tr>
                    <td>{{ $vendor['idvendor'] }}</td>
                    <td>{{ $vendor['nama_vendor'] }}</td>
                    <td>
                    <form action="{{ route('vendor.updateStatusBH', $vendor['idvendor']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="1" {{ $vendor['status'] == '1' ? 'selected' : '' }}>Y</option>
                            <option value="0" {{ $vendor['status'] == '0' ? 'selected' : '' }}>N</option>
                        </select>
                    </form>
                    <td>
                    <form action="{{ route('vendor.updateStatus', $vendor['idvendor']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="1" {{ $vendor['status'] == '1' ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ $vendor['status'] == '0' ? 'selected' : '' }}>Not Available</option>
                        </select>
                    </form>
                </td>
                    <td>
                        <a href="{{ route('vendor.edit', $vendor['idvendor']) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                        <form action="{{ route('vendor.destroy', $vendor['idvendor']) }}" method="POST" style="display:inline;">
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
