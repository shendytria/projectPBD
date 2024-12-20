@extends('layout.main')

@section('content')
    <div class="container">
        <h2>Daftar Barang</h2>
        <a href="{{ route('barang.create') }}" class="btn btn-outline-primary">Tambah Barang</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped mt-3">
            <thead>
                <tr class="table-dark">
                    <th>Jenis</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                    <tr>
                        <td>{{ $barang['jenis'] }}</td>
                        <td>{{ $barang['nama'] }}</td>
                        <td>{{ $barang['nama_satuan'] }}</td>
                        <td>{{ number_format($barang['harga'], 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('barang.updateStatus', $barang['idbarang']) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="1" {{ $barang['status'] == '1' ? 'selected' : '' }}>Available</option>
                                    <option value="0" {{ $barang['status'] == '0' ? 'selected' : '' }}>Not Available</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('barang.edit', $barang['idbarang']) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                            <form action="{{ route('barang.destroy', $barang['idbarang']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
