@extends('layout.conquer')
@section('title')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Daftar Batch Kadaluarsa</h1>

    <div class="container">
        <!-- Search Bar -->
        <form method="GET" action="{{ route('produks.daftarKadaluarsa') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari..."
                    value="{{ $search }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary mb-2">Search</button>
                </div>
            </div>
        </form>
        
        <form method="GET" action="{{ route('produks.reportKadaluarsa') }}">
            <label for="groupBy">Lihat Laporan Kadaluarsa:</label>
            <select name="filter" id="groupBy" class="form-select w-auto d-inline mx-2">
                <option value="">-- Pilih Grup --</option>
                <option value="day">Hari Ini</option>
                <option value="week">Minggu Ini</option>
                <option value="month">Bulan Ini</option>
                <option value="year">Tahun Ini</option>
            </select>
            <button type="submit" class="btn btn-primary">Lihat</button>
        </form>

        <p>Daftar batch kadaluarsa yang tercatat di apotek.</p>

        <!-- Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    @foreach ([
            'batch_id' => 'Batch ID',
            'nama_produk' => 'Nama Produk',
            'stok' => 'Stok Produk',
            'nama_satuan' => 'Satuan Batch',
            'hpp' => 'HPP Produk',
            'total_harga' => 'Total Harga',
            'tgl_kadaluarsa' => 'Tanggal Kadaluarsa'
        ] as $column => $label)
                        <th>
                            <a
                                href="{{ route('produks.daftarKadaluarsa', ['sort_by' => $column, 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">
                                {{ $label }}
                                @if ($sortBy == $column)
                                    {{ $sortOrder == 'asc' ? '▲' : '▼' }}
                                @endif
                            </a>
                        </th>
                    @endforeach
                    {{-- <th>Aksi</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($datas as $d)
                    <tr>
                        <td>{{ $d->batch_id ?? '-' }}</td>
                        <td>{{ $d->nama_produk ?? '-' }}</td>
                        <td>{{ $d->stok ?? '-' }}</td>
                        <td>{{ $d->nama_satuan ?? '-' }}</td>
                        <td>RP {{ number_format($d->hpp ?? 0, 0, ',', '.') }}</td>{{-- ?? '-' -> supaya tetap bisa tampil walau null --}}
                        <td>RP RP {{ number_format($d->total_harga ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $d->tgl_kadaluarsa }}</td>
                        {{-- <td> --}}
                            {{-- <a class="btn btn-warning" href="{{ route('notabelis.edit', $d->id) }}">Edit</a> --}}
                            {{-- <a href="{{ route('notabelis.print', $d->notabeli->id) }}" class="btn btn-secondary btn-sm"
                                target="_blank">
                                Cetak Nota
                            </a> --}}
                            {{-- <form method="POST" action="{{ route('notabelis.destroy', $d->notabelis_id) }}"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete" class="btn btn-danger"
                                    onclick="return confirm('Are you sure to delete Nota {{ $d->notabelis_id }}?');">
                            </form> --}}
                        {{-- </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $datas->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
