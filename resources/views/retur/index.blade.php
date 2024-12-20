@extends('layout.main')

@section('content')
<div class="container">
    <h2>Daftar Retur</h2>
    <table class="table table-stripped">
        <thead>
            <tr class="table-dark">
                <th>NO</th>
                <th>ID Penerimaan</th>
                <th>Nama</th>
                <th>Jumlah Retur</th>
                <th>Alasan</th>
                <th>Waktu Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($retur as $item)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $item['idpenerimaan'] }}</td>
                <td>
                    @foreach ($users as $userItem)
                    @if ($userItem['iduser'] == $item['iduser']){{ $userItem['username'] }}
                    @endif
                    @endforeach
                </td>
                <td>{{ $item['jumlah'] }}</td>
                <td>{{ $item['alasan'] }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($item['created_at'])->format('d-m-Y H:i:s') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

</div>
@endsection
