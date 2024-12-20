@extends('layout.main')

@section('content')
<div class="container">
    <h2>Daftar Penerimaan</h2>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr class="table-dark">
                <th>NO</th>
                <th>Nama</th>
                <th>Barang</th>
                <th>ID Pengadaan</th>
                <th>Jumlah Pengadaan</th>
                <th>Jumlah Diterima</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penerimaanList as $penerimaan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $penerimaan['username'] }}</td>
                <td>{{ $penerimaan['nama_barang'] }}</td>
                <td>{{ $penerimaan['idpengadaan'] }}</td>
                <td>{{ $penerimaan['jumlah_pengadaan'] }}</td>
                <td>{{ $penerimaan['jumlah_terima'] }}</td>
                <td>
                    <a href="{{ route('penerimaan.show', $penerimaan['idpenerimaan']) }}" class="btn btn-outline-danger btn-sm">Pengembalian</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data yang tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <script>
        function fetchPenerimaanData() {
            $.ajax({
                url: '{{ route("penerimaan.index") }}', // Pastikan ini adalah route yang benar
                method: 'GET',
                success: function(response) {
                    const penerimaanList = response.penerimaanList;
                    let htmlContent = '';

                    penerimaanList.forEach(item => {
                        htmlContent += `
                        <tr>
                            <td>${item.username}</td>
                            <td>${item.nama_barang}</td>
                            <td>${item.jumlah_pengadaan}</td>
                            <td>${item.jumlah_terima}</td>
                            <td>${item.created_at}</td>
                            <td>${item.keluar}</td>
                            <td>${item.masuk}</td>
                            <td>${item.stock}</td>
                            <td>${item.status == 3 ? 'Diterima' : 'Diproses'}</td>
                        </tr>
                    `;
                    });

                    $('#penerimaanTable tbody').html(htmlContent); // Pastikan ini adalah ID yang benar untuk tabel Anda
                },
                error: function() {
                    console.log('Error fetching penerimaan data.');
                }
            });
        }

        // Memanggil fungsi fetchPenerimaanData setiap 10 detik (10000ms)
        setInterval(fetchPenerimaanData, 10000);

        // Memanggil langsung untuk pertama kali saat halaman dimuat
        fetchPenerimaanData();
    </script>

</div>
@endsection
