@extends('layout.main')

@section('content')
    <div class="container">
        <h2>Daftar Satuan</h2>
        <a href="{{ route('satuan.create') }}" class="btn btn-outline-primary">Tambah Satuan</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped mt-3">
            <thead>
                <tr class="table-dark">
                    <th>ID</th>
                    <th>Nama Satuan</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($satuans as $satuan)
        <tr>
            <td>{{ $satuan['idsatuan'] }}</td> <!-- Use array syntax -->
            <td>{{ $satuan['nama_satuan'] }}</td> <!-- Use array syntax -->
            <td>
                    <form action="{{ route('satuan.updateStatus', $satuan['idsatuan']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="1" {{ $satuan['status'] == '1' ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ $satuan['status'] == '0' ? 'selected' : '' }}>Not Available</option>
                        </select>
                    </form>
                </td>
            <td>
                <a href="{{ route('satuan.edit', $satuan['idsatuan']) }}" class="btn btn-outline-warning btn-sm">Edit</a> <!-- Use array syntax -->
                <form action="{{ route('satuan.destroy', $satuan['idsatuan']) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus satuan ini?')">Hapus</button>
                </form>
            </td>
        </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
