@extends('layout.main')

@section('content')
<div class="container">
    <h2>Permintaan Penerimaan #{{ $pengadaan->idpengadaan ?? '' }}</h2>

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($details) == 0)
    <div class="alert alert-warning">Tidak ada detail pengadaan ditemukan.</div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Harga Satuan</th>
                <th>Permintaan</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $detail)
            <tr>
                <td>{{ $detail->nama }}</td>
                <td>{{ number_format($detail->harga_satuan, 2) }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>{{ number_format($detail->sub_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <form action="{{ route('pengadaan.update', $pengadaan->idpengadaan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mt-4">
            <strong>Jumlah</strong>
            @foreach($details as $index => $detail)
            <div class="mb-3">
                <input
                    type="number"
                    name="jumlah_terima[{{ $index }}]"
                    id="jumlah_terima_{{ $index }}"
                    class="form-control w-auto"
                    min="1"
                    max="{{ $detail->jumlah ?? 0 }}"
                    placeholder="0"
                    required>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-outline-success">Terima</button>
        <a href="{{ url('pengadaan') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
    @endif
</div>
@endsection
