@extends('layout.main')

@section('content')
<div class="container">
    <h2>Daftar Pengadaan</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('pengadaan.create') }}" class="btn btn-outline-primary">Tambah Pengadaan</a>

    <table class="table table-striped mt-3">
        <thead>
            <tr class="table-dark">
                <th>NO</th>
                <th>Nama</th>
                <th>Vendor</th>
                <th>Subtotal</th>
                <th>PPN (11%)</th>
                <th>Total Nilai</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengadaanList as $pengadaan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pengadaan->username }}</td>
                <td>{{ $pengadaan->nama_vendor }}</td>
                <td>Rp {{ number_format($pengadaan->subtotal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($pengadaan->ppn, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</td>
                <td>
                    @php
                    $statusLabels = [
                    1 => 'Menunggu',
                    2 => 'Diproses',
                    3 => 'Disetujui',
                    4 => 'Dibatalkan',
                    ];
                    @endphp
                    <span class="{{
                    $pengadaan->status == 1 ? 'text-warning' :
                    ($pengadaan->status == 2 ? 'text-primary' :
                    ($pengadaan->status == 3 ? 'text-success' :
                    ($pengadaan->status == 4 ? 'text-danger' : 'text-secondary'))) }}">
                        {{ $statusLabels[$pengadaan->status] }}
                    </span>
                </td>
                <td>
                    @if($pengadaan->status == 1 || $pengadaan->status == 2) <!-- Check if status is 'Diproses' -->
                    <a href="{{ route('pengadaan.show', $pengadaan->idpengadaan) }}" class="btn btn-outline-success btn-sm">Terima</a>
                    <a href="{{ route('pengadaan.cancel', $pengadaan->idpengadaan) }}" class="btn btn-outline-danger btn-sm">Batalkan</a>
                    @elseif($pengadaan->status == 3) <!-- Optionally display a message or disable the button -->
                    <button class="btn btn-light btn-sm" disabled>Diterima</button>
                    @elseif($pengadaan->status == 4) <!-- If it's canceled, show canceled button -->
                    <button class="btn btn-light btn-sm" disabled>Dibatalkan</button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data pengadaan tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tambahkan pagination jika diperlukan --}}
    @if(method_exists($pengadaanList, 'links'))
    <div class="mt-3">
        {{ $pengadaanList->links() }}
    </div>
    @endif
</div>
@endsection
