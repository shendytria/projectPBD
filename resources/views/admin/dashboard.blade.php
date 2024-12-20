@extends('layout.main')

@section('content')
<div class="container">
    <h2>Kartu Stock Barang</h2>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-striped">
                <thead>
                    <tr class="table-dark">
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jenis Transaksi</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Stock</th>
                        <th>ID Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kartu_stok as $item)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenis_transaksi }}</td>
                            <td>{{ $item->masuk }}</td>
                            <td>{{ $item->keluar }}</td>
                            <td>{{ $item->stock }}</td>
                            <td>{{ $item->idtransaksi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
