@extends('layout.conquer')
@section('title')
@section('content')
    @if ($errors->any()) untuk memunculkan error
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Buat Racikan Baru</h1>

    <form action="{{ route('racikans.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama">Nama Racikan</label>
            <input type="text" class="form-control" name="nama" aria-describedby="nameHelp"
                placeholder="Masukkan Nama Racikan">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi Racikan</label>
            <textarea class="form-control" name="deskripsi" rows="4" placeholder="Masukkan Deskripsi Racikan"></textarea>
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="nama_dokter">Nama Dokter</label>
            <input type="text" class="form-control" name="nama_dokter" aria-describedby="nameHelp"
                placeholder="Masukkan Nama Dokter">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="nama_pasien">Nama Pasien</label>
            <input type="text" class="form-control" name="nama_pasien" aria-describedby="nameHelp"
                placeholder="Masukkan Nama Pasien">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="aturan_pakai">Aturan Pemakaian</label>
            <textarea class="form-control" name="aturan_pakai" rows="4" placeholder="Masukkan Aturan Pakai Racikan"></textarea>
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="biaya_embalase">Biaya Embalase</label>
            <input type="text" class="form-control" name="biaya_embalase" aria-describedby="nameHelp"
                placeholder="Masukkan Biaya Racikan">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>

        <h4>Produk Komposisi</h4>
        <div id="produk-list">
            <div class="produk-item">
                <select name="produks_id[]" class="produk-select">
                    @foreach ($produks as $produk)
                        <option value="{{ $produk->id }}">{{ $produk->nama }}</option>
                    @endforeach
                </select>
                <input type="number" name="quantity[]" placeholder="Jumlah" required>
            </div>
        </div>
        <button type="button" onclick="addProduk()" class="btn btn-info mt-3 me-2">Tambah Produk</button>
        <button type="submit" class="btn btn-primary mt-3 me-2">Simpan</button>
        <a href="{{ route('racikans.index') }}"
            class="btn btn-primary bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
    </form>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        function addProduk() {
            const item = document.querySelector('.produk-item');
            const clone = item.cloneNode(true);

            // Clear quantity input
            clone.querySelector('input[name="quantity[]"]').value = '';

            // Remove any previous Select2 instance
            const select = clone.querySelector('select[name="produks_id[]"]');
            $(select).next('.select2-container').remove(); // Remove old UI
            $(select).removeAttr('data-select2-id').val(''); // Reset value

            document.getElementById('produk-list').appendChild(clone);

            // Re-initialize Select2 on the new select
            $(select).select2({
                placeholder: "Cari produk terbatas / keras...",
                allowClear: true
            });
        }

        // Initialize Select2 for the first one on page load
        $(document).ready(function() {
            $('select[name="produks_id[]"]').select2({
                placeholder: "Cari produk terbatas / keras...",
                allowClear: true
            });
        });
    </script>
@endsection
