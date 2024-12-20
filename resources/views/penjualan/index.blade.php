@extends('layout.main')

@section('content')

        <h2 class="container">Penjualan</h2>

        <div class=" mb-3">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="table-dark">
                                <th>NO</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                                <th>Total Harga</th>
                                <th>Tanggal Penjualan</th>
                            </tr>
                        </thead>
                    <tbody>
                            @foreach ($detail_penjualan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['nama_barang'] }}</td>
                                    <td>{{ $item['jumlah'] }}</td>
                                    <td>Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['total_nilai'], 0, ',', '.') }}</td>
                                    <td>{{ $item['created_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>

        <div class="row">
            @foreach ($penjualan as $item)
                <div class="col-3">
                    <div class="card shadow-lg hover-shadow-lg transition-transform hover-scale-105">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item['nama'] }}</h5>
                            <p class="card-text">Stock: {{ $item['stock'] }}</p>
                            <p class="card-text">{{ 'Rp ' . number_format($item['harga'], 0, ',', '.') }}</p>
                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-success btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#buyModal{{ $item['idbarang'] }}">Beli</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="buyModal{{ $item['idbarang'] }}" tabindex="-1"
                    aria-labelledby="buyModalLabel{{ $item['idbarang'] }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="buyModalLabel{{ $item['idbarang'] }}">Konfirmasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Anda pergi untuk membeli <span class="fw-bold">{{ $item['nama'] }}</span> dengan harga
                                    {{ 'Rp ' . number_format($item['harga'], 0, ',', '.') }}
                                </p>

                                <!-- Quantity Selector -->
                                <form action="{{ route('penjualan.checkout') }}" method="post">
                                    @csrf
                                    <p class="mb-2">Berapa banyak?</p>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <button type="button" onclick="decrementQty({{ $item['idbarang'] }})"
                                            class="btn btn-sm btn-outline-secondary">-</button>
                                        <input type="text" id="qty{{ $item['idbarang'] }}" name="jumlah" value="1"
                                            min="1" data-max="{{ $item['stock'] }}"
                                            class="form-control text-center w-auto" readonly>
                                        <button type="button" onclick="incrementQty({{ $item['idbarang'] }})"
                                            class="btn btn-sm btn-outline-secondary">+</button>
                                    </div>
                                    <input type="hidden" id="harga_satuan{{ $item['idbarang'] }}" name="harga_satuan"
                                        value="{{ $item['harga'] }}">
                                    <input type="hidden" name="idbarang" value="{{ $item['idbarang'] }}">

                                    <p class="mb-2">Pilih pengguna</p>
                                    <select class="form-select mb-3" name="iduser" required>
                                        @foreach ($users as $userItem)
                                            <option value="{{ $userItem['iduser'] }}">{{ $userItem['username'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <p class="mb-2">Keuntungan</p>
                                    <select class="form-select mb-3" id="marginSelect{{ $item['idbarang'] }}"
                                        name="iduser" required onchange="updateTotals({{ $item['idbarang'] }})">
                                        @foreach ($margin_penjualan as $marginItem)
                                            <option value="{{ $marginItem['idmargin_penjualan'] }}">
                                                {{ $marginItem['persen'] }} %
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="hidden" id="hiddenSubTotal{{ $item['idbarang'] }}" name="sub_total">
                                    <input type="hidden" id="hiddenPpn{{ $item['idbarang'] }}" name="ppn">
                                    <input type="hidden" id="hiddenMargin{{ $item['idbarang'] }}" name="margin">
                                    <input type="hidden" id="hiddenTotal{{ $item['idbarang'] }}" name="total">

                                    <div class="my-4">
                                        <div class="d-flex justify-content-between">
                                            <p>Sub Total</p>
                                            <p id="subTotal{{ $item['idbarang'] }}">Rp.0</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p>PPN 11%</p>
                                            <p id="ppn{{ $item['idbarang'] }}">Rp.0</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p>Margin</p>
                                            <p id="margin{{ $item['idbarang'] }}">Rp.0</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p>Total</p>
                                            <p id="total{{ $item['idbarang'] }}">Rp.0</p>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-outline-success w-100">Beli</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function updateTotals(idbarang) {
                        const hargaSatuan = parseFloat(document.getElementById(`harga_satuan${idbarang}`).value);
                        const qtyInput = document.getElementById(`qty${idbarang}`);
                        const marginSelect = document.getElementById(`marginSelect${idbarang}`);
                        const qty = parseInt(qtyInput.value) || 1;
                        const marginPercent = parseFloat(marginSelect.value) || 0;

                        // Calculate subtotal
                        const subTotal = hargaSatuan * qty;

                        // Calculate PPN (11%)
                        const ppn = subTotal * 0.11;

                        // Calculate margin amount
                        const margin = (subTotal * marginPercent) / 100;

                        // Calculate total
                        const total = subTotal + ppn + margin;

                        // Update UI
                        document.getElementById(`subTotal${idbarang}`).textContent = subTotal.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });
                        document.getElementById(`ppn${idbarang}`).textContent = ppn.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });
                        document.getElementById(`margin${idbarang}`).textContent = margin.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });
                        document.getElementById(`total${idbarang}`).textContent = total.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });


                        document.getElementById(`hiddenSubTotal${idbarang}`).value = subTotal;
                        document.getElementById(`hiddenPpn${idbarang}`).value = ppn;
                        document.getElementById(`hiddenMargin${idbarang}`).value = margin;
                        document.getElementById(`hiddenTotal${idbarang}`).value = total;
                    }

                    function incrementQty(idbarang) {
                        const qtyInput = document.getElementById(`qty${idbarang}`);
                        const maxQty = parseInt(qtyInput.dataset.max);
                        let qty = parseInt(qtyInput.value) || 1;

                        if (qty < maxQty) {
                            qtyInput.value = ++qty;
                            updateTotals(idbarang);
                        }
                    }

                    function decrementQty(idbarang) {
                        const qtyInput = document.getElementById(`qty${idbarang}`);
                        let qty = parseInt(qtyInput.value) || 1;

                        if (qty > 1) {
                            qtyInput.value = --qty;
                            updateTotals(idbarang);
                        }
                    }

                    // Initial calculation on load for each item
                    updateTotals({{ $item['idbarang'] }});
                </script>
            @endforeach

        </div>
    </div>

@endsection
