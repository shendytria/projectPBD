<!-- resources/views/barang/create.blade.php -->
@extends('layout.main')

@section('content')
    <div class="container">
        <h2>Barang</h2>

        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis Barang</label>
                <select name="jenis" id="jenis" class="form-control">
                    <option value="" disabled selected>Pilih jenis barang</option>
                    <option value="0">Makanan</option>
                    <option value="1">Minuman</option>
                </select>
                @error('jenis')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Barang</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukkan nama barang">
                @error('nama')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="idsatuan" class="form-label">Satuan</label>
                <select name="idsatuan" id="idsatuan" class="form-control">
                <option value="" disabled selected>Pilih nama satuan</option>
                    @foreach ($satuans as $satuan)
                        <option value="{{ $satuan['idsatuan'] }}">{{ $satuan['nama_satuan'] }}</option>
                    @endforeach
                </select>
                @error('idsatuan')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga') }}" placeholder="Rp">
                @error('harga')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-outline-primary">Simpan</button>
            <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
@endsection
