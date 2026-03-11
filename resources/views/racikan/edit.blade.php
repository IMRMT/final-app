@extends('layout.conquer')
@section('title')
@section('content')

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Ubah Racikan</h1>

    <form method="POST" action="{{ route('racikans.update', $datas->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama">Nama Racikan</label>
            <input type="text" class="form-control" name="nama" aria-describedby="nameHelp"
                placeholder="Masukkan Nama Racikan" value="{{ $datas->nama }}">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="nama_dokter">Nama Dokter</label>
            <input type="text" class="form-control" name="nama_dokter" aria-describedby="nameHelp"
                placeholder="Masukkan Nama Dokter" value="{{ $datas->nama_dokter }}">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="alamat_dokter">Alamat Dokter</label>
            <textarea name="alamat_dokter" class="form-control">{{ old('alamat_dokter', $datas->alamat_dokter) }}</textarea>
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="nama_pasien">Nama Pasien</label>
            <input type="text" class="form-control" name="nama_pasien" aria-describedby="nameHelp"
                placeholder="Masukkan Nama Pasien" value="{{ $datas->nama_pasien }}">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="alamat_pasien">Alamat Pasien</label>
            <textarea name="alamat_pasien" class="form-control">{{ old('alamat_pasien', $datas->alamat_pasien) }}</textarea>
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi Racikan</label>
            <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $datas->deskripsi) }}</textarea>
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="aturan_pakai">Aturan Pakai Racikan</label>
            <textarea name="aturan_pakai" class="form-control">{{ old('aturan_pakai', $datas->aturan_pakai) }}</textarea>
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="biaya_embalase">Biaya Embalase</label>
            <input type="text" class="form-control" name="biaya_embalase" aria-describedby="nameHelp"
                placeholder="Masukkan Biaya Racikan" value="{{ $datas->biaya_embalase }}">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="tgl_ambil">Tanggal Ambil</label>
            <input type="date" class="form-control" name="tgl_ambil" aria-describedby="dateHelp"
                value="{{ $datas->tgl_ambil }}">
            <small id="dateHelp" class="form-text text-muted">Pilih tanggal ambil racikan</small>
        </div>

        <h4>Produk Komposisi</h4>
        <div id="produk-list">
            @forelse ($komposisi as $k)
                <div class="produk-item mb-2 d-flex align-items-center">
                    <input type="hidden" name="komposisi_id[]" value="{{ $k->id }}">
                    <select name="produks_id[]" class="form-select me-2">
                        @foreach ($produks as $produk)
                            <option value="{{ $produk->id }}" {{ $k->produks_id == $produk->id ? 'selected' : '' }}>
                                {{ $produk->nama }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity[]" class="form-control me-2" value="{{ $k->quantity }}" required>
                </div>
            @empty
                <div class="produk-item mb-2 d-flex align-items-center">
                    <input type="hidden" name="komposisi_id[]" value="">
                    <select name="produks_id[]" class="form-select me-2">
                        @foreach ($produks as $produk)
                            <option value="{{ $produk->id }}">{{ $produk->nama }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity[]" class="form-control me-2" value="" required>
                </div>
            @endforelse
        </div>

        <button type="button" onclick="addProduk()" class="btn btn-info mt-3 me-2">Tambah Produk</button>
        <button type="submit" class="btn btn-primary mt-3 me-2">Simpan</button>
        <a href="{{ route('racikans.index') }}"
            class="btn btn-primary bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
    </form>

    <script>
        function addProduk() {
            const item = document.querySelector('.produk-item');
            const clone = item.cloneNode(true);

            // Clear values in cloned inputs
            clone.querySelector('select').selectedIndex = 0;
            clone.querySelector('input[type=number]').value = '';
            const hiddenId = clone.querySelector('input[type=hidden]');
            if (hiddenId) hiddenId.value = '';

            document.getElementById('produk-list').appendChild(clone);
        }
    </script>

@endsection
