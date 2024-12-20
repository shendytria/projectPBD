@extends('layout.main')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Proses Pengembalian</h2>

    <!-- Menampilkan pesan error jika ada -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form untuk proses retur -->
    <form action="{{ route('penerimaan.update', $penerimaan['idpenerimaan']) }}" method="POST">
        @csrf
        @method('PUT')
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Barang</th>
                    <th>Jumlah Pengembalian</th>
                    <th>Alasan Pengembalian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $index => $detail)
                    <tr>
                        <!-- Menampilkan nama barang -->
                        <td>{{ $detail['nama'] }}</td>

                        <!-- Input jumlah retur -->
                        <td>
                            <input
                                type="number"
                                name="retur[{{ $index }}][jumlah]"
                                class="form-control"
                                min="1"
                                max="{{ $detail['jumlah_terima'] }}"
                                placeholder="0"
                                required
                                aria-label="Jumlah retur untuk barang {{ $detail['nama'] }}"
                            >
                            <input type="hidden" name="retur[{{ $index }}][idbarang]" value="{{ $detail['idbarang'] }}">
                        </td>

                        <!-- Input alasan retur -->
                        <td>
                            <textarea
                                name="retur[{{ $index }}][alasan]"
                                class="form-control"
                                placeholder="Tulis alasan pengembalian {{ $detail['nama'] }}"
                                required
                                aria-label="Alasan pengembalian untuk barang {{ $detail['nama'] }}"
                            ></textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tombol Submit dan Batal -->
            <button type="submit" class="btn btn-outline-danger">Submit Retur</button>
            <a href="{{ route('penerimaan.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div>
@endsection
