@extends('layout.main')

@section('content')
    <div class="container">
        <h2>Satuan</h2>

        <form action="{{ route('satuan.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="nama_satuan" class="form-label">Nama Satuan</label>
                <input type="text" name="nama_satuan" class="form-control" placeholder="Masukkan nama satuan" required>
            </div>

            <!-- <div class="form-group mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div> -->

            <button type="submit" class="btn btn-outline-primary">Simpan</button>
            <a href="{{ route('satuan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
@endsection
