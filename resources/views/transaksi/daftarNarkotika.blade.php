@extends('layout.conquer')
@section('title', 'Daftar Nota Penjualan Racikan')
@section('content')

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Daftar Nota Penjualan Narkotika dan Psikotropika</h1>

    <a href="{{ route('racikan') }}" class="btn btn-primary mb-3">Create New Nota Penjualan</a>

    <div class="container">
        <form method="GET" action="{{ route('racikans.daftarNarkotika') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari racikan..."
                    value="{{ $search }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary mb-2">Search</button>
                </div>
            </div>
        </form>

        <form method="GET" action="{{ route('racikans.reportNarkotika') }}">
            <label for="groupBy">Lihat Laporan Narkotika:</label>
            <select name="filter" id="groupBy" class="form-select w-auto d-inline mx-2">
                <option value="">-- Pilih Grup --</option>
                <option value="day">Hari Ini</option>
                <option value="week">Minggu Ini</option>
                <option value="month">Bulan Ini</option>
                <option value="year">Tahun Ini</option>
            </select>
            <button type="submit" class="btn btn-primary">Lihat</button>
        </form>

        <p>Daftar transaksi penjualan narkotika dan psikotropika yang tercatat di apotek.</p>

        <table class="table table-striped">
            <thead>
                <tr>
                    @foreach ([
            'racikan_id' => 'Racikan ID',
            'batch_id' => 'Batch ID',
            'nama_produk' => 'Nama Obat',
            'nama_satuan' => 'Satuan',
            'stok_awalbulan' => 'Stok Awal Bulan',
            'nama_distributor' => 'Distributor',
            'stok_diterima' => 'Jumlah Diterima',
            'stok_keluar' => 'Jumlah Dipakai',
            'stok_akhirbulan' => 'Stok Akhir Bulan',
            'nama_pasien' => 'Nama Pasien',
            'alamat_pasien' => 'Alamat Pasien',
            'nama_dokter' => 'Nama Dokter',
            'alamat_dokter' => 'Alamat Dokter',
            'tgl_ambil' => 'Tanggal Ambil',
        ] as $column => $label)
                        <th>
                            <a
                                href="{{ route('racikans.daftarNarkotika', [
                                    'sort_by' => $column,
                                    'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc',
                                    'search' => $search,
                                ]) }}">
                                {{ $label }}

                                @if ($sortBy == $column)
                                    {{ $sortOrder == 'asc' ? '▲' : '▼' }}
                                @endif

                            </a>
                        </th>
                    @endforeach

                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($datas as $d)
                    <tr>
                        <td>{{ $d->racikan_id }}</td>
                        <td>{{ $d->batch_id }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td>{{ $d->nama_satuan }}</td>
                        <td>{{ $d->stok_awalbulan }}</td>
                        <td>{{ $d->nama_distributor }}</td>
                        <td>{{ $d->stok_diterima }}</td>
                        <td>{{ $d->stok_keluar }}</td>
                        <td>{{ $d->stok_akhirbulan }}</td>
                        <td>{{ $d->nama_pasien }}</td>
                        <td>{{ $d->alamat_pasien }}</td>
                        <td>{{ $d->nama_dokter }}</td>
                        <td>{{ $d->alamat_dokter }}</td>
                        <td>{{ $d->tgl_ambil }}</td>
                        <td>
                            <a href="{{ route('racikans.printNarkotika', $d->racikan_id) }}"
                                class="btn btn-secondary btn-sm" target="_blank">Cetak Nota</a>
                            {{-- <form method="POST" action="{{ route('notajuals.destroy', $d->notajuals_id) }}"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete" class="btn btn-danger"
                                    onclick="return confirm('Are you sure to delete Nota {{ $d->notajuals_id }}?');">
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $datas->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
