<!-- resources/views/barang/edit.blade.php -->
@extends('layout.main')

@section('content')
    <div class="container">
        <h2>Edit Barang</h2>

        <form action="{{ route('barang.update', $barang['idbarang']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis Barang</label>
                <select name="jenis" id="jenis" class="form-control">
                    <option value="0">Makanan</option>
                    <option value="1">Minuman</option>
                </select>
                @error('jenis')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Barang</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $barang['nama']) }}">
                @error('nama')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="idsatuan" class="form-label">Satuan</label>
                <select name="idsatuan" id="idsatuan" class="form-control">
                    @foreach ($satuans as $satuan)
                        <option value="{{ $satuan['idsatuan'] }}" {{ $barang['idsatuan'] == $satuan['idsatuan'] ? 'selected' : '' }}>
                            {{ $satuan['nama_satuan'] }}
                        </option>
                    @endforeach
                </select>
                @error('idsatuan')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga', $barang['harga']) }}">
                @error('harga')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <!-- <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ $barang['status'] == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $barang['status'] == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')<div class="text-danger">{{ $message }}</div>@enderror
            </div> -->

            <button type="submit" class="btn btn-outline-success">Update</button>
        </form>
    </div>
@endsection
