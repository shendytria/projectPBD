@extends('layout.main')

@section('content')
<div class="container">
    <h2>Pengadaan</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('pengadaan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_iduser" class="form-label">Pengguna</label>
            <select name="user_iduser" id="user_iduser" class="form-control" required>
                <option value="">Pilih nama pengguna</option>
                @foreach($users as $user)
                <option value="{{ $user['iduser'] }}" @if(old('user_iduser')==$user['iduser']) selected @endif>
                    {{ $user['username'] }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="vendor_idvendor" class="form-label">Vendor</label>
            <select name="vendor_idvendor" id="vendor_idvendor" class="form-control" required>
                <option value="">Pilih nama vendor</option>
                @foreach($vendors as $vendor)
                <option value="{{ $vendor['idvendor'] }}" @if(old('vendor_idvendor')==$vendor['idvendor']) selected @endif>
                    {{ $vendor['nama_vendor'] }}
                </option>
                @endforeach
            </select>
        </div>

        <div id="item-details">
            <div class="item-detail row">
                <div class="col-md-3">
                    <label for="id_barang_0" class="form-label">Barang</label>
                    <select name="items[0][id_barang]" id="id_barang_0" class="form-control" required onchange="updateHargaSatuan(0)">
                        <option value="">Pilih nama barang</option>
                        @foreach($barangs as $barang)
                        <option value="{{ $barang['idbarang'] }}" data-harga="{{ $barang['harga'] }}">
                            {{ $barang['nama'] }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="jumlah_0" class="form-label">Jumlah</label>
                    <input type="number" name="items[0][jumlah]" id="jumlah_0" class="form-control" placeholder="0" required oninput="calculateSubtotal(0)">
                </div>

                <div class="col-md-2">
                    <label for="harga_satuan_0" class="form-label">Harga Satuan</label>
                    <input type="number" name="items[0][harga_satuan]" id="harga_satuan_0" class="form-control" placeholder="Rp" readonly>
                </div>

                <div class="col-md-2">
                    <label for="subtotal_0" class="form-label">Subtotal</label>
                    <input type="number" name="items[0][subtotal]" id="subtotal_0" class="form-control" placeholder="Rp" readonly>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger remove-item btn-sm">Hapus</button>
                </div>
            </div>
        </div>

        <!-- Button to add more items -->
        <button type="button" class="btn btn-light mt-3 add-item"><i class="fas fa-plus"></i></button>

        <!-- Total Calculation -->
        <div class="form-group mt-3">
            <label for="total" class="form-label btn-sm">Total</label>
            <input type="number" name="total" id="total" class="form-control" value="0" readonly>
        </div>

        <input type="hidden" name="status" value="{{ $initialStatus }}">

        <!-- Submit Button -->
        <button type="submit" class="btn btn-outline-primary mt-3">Simpan</button>
        <a href="{{ url('pengadaan') }}" class="btn btn-outline-secondary mt-3">Batal</a>
    </form>

    <!-- JavaScript to handle dynamic item addition, harga satuan, and subtotal calculation -->
    <script>
        let itemCount = 1;

        // Function to update harga_satuan when a barang is selected
        function updateHargaSatuan(index) {
            const barangSelect = document.getElementById('id_barang_' + index);
            const hargaSatuan = barangSelect.options[barangSelect.selectedIndex].getAttribute('data-harga');
            document.getElementById('harga_satuan_' + index).value = hargaSatuan || 0; // If no harga found, set to 0
            calculateSubtotal(index);
        }

        // Function to calculate the subtotal for each item
        function calculateSubtotal(index) {
            const jumlah = document.getElementById('jumlah_' + index).value;
            const hargaSatuan = document.getElementById('harga_satuan_' + index).value;
            const subtotal = jumlah * hargaSatuan;
            document.getElementById('subtotal_' + index).value = subtotal || 0; // Set to 0 if NaN

            // Update the total whenever a subtotal changes
            updateTotal();
        }

        // Function to update the total
        function updateTotal() {
            let total = 0;
            for (let i = 0; i < itemCount; i++) {
                const subtotalValue = parseFloat(document.getElementById('subtotal_' + i).value) || 0;
                total += subtotalValue;
            }
            document.getElementById('total').value = total.toFixed(2); // Set total with two decimal places
        }

        // Add more rows dynamically
        document.querySelector('.add-item').addEventListener('click', function() {
            const itemDetailsDiv = document.getElementById('item-details');
            const newItem = `
                <div class="item-detail row mt-3">
                    <div class="col-md-3">
                        <label for="id_barang_${itemCount}" class="form-label">Barang:</label>
                        <select name="items[${itemCount}][id_barang]" id="id_barang_${itemCount}" class="form-control" required onchange="updateHargaSatuan(${itemCount})">
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang['idbarang'] }}" data-harga="{{ $barang['harga'] }}">
                                    {{ $barang['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="jumlah_${itemCount}" class="form-label">Jumlah:</label>
                        <input type="number" name="items[${itemCount}][jumlah]" id="jumlah_${itemCount}" class="form-control" placeholder="0" required oninput="calculateSubtotal(${itemCount})">
                    </div>

                    <div class="col-md-2">
                        <label for="harga_satuan_${itemCount}" class="form-label">Harga Satuan:</label>
                        <input type="number" name="items[${itemCount}][harga_satuan]" id="harga_satuan_${itemCount}" class="form-control" placeholder="Rp" readonly>
                    </div>

                    <div class="col-md-2">
                        <label for="subtotal_${itemCount}" class="form-label">Subtotal:</label>
                        <input type="number" name="items[${itemCount}][subtotal]" id="subtotal_${itemCount}" class="form-control" placeholder="Rp" readonly>
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-item">Hapus</button>
                    </div>
                </div>`;
            itemDetailsDiv.insertAdjacentHTML('beforeend', newItem);
            itemCount++;
        });

        // Remove item row
        document.querySelector('#item-details').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-item')) {
                event.target.closest('.row.item-detail').remove();
            }
        });
    </script>
</div>
@endsection
