@extends('layout.main')

@section('content')
    <div class="container">
        <h2>Edit Satuan</h2>

        <form action="{{ route('satuan.update', $satuan['idsatuan']) }}" method="POST"> <!-- Use array syntax -->
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_satuan" class="form-label">Nama Satuan</label>
                <input type="text" name="nama_satuan" class="form-control" value="{{ $satuan['nama_satuan'] }}" required> <!-- Use array syntax -->
            </div>


            <button type="submit" class="btn btn-outline-primary mt-3">Update</button>
        </form>
    </div>
@endsection
