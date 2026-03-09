@extends('layout.conquer')
@section('title')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Daftar Stok Opname</h1>

    <a href="{{ route('opnames.create') }}" class="btn btn-primary mb-3">Create New Stok Opname</a>

    <form method="GET" action="{{ route('opnames.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari Stok Opname..."
                value="{{ $search }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary mb-3">Search</button>
            </div>
        </div>
    </form>

    <div class="container">
        <h2>Stok Opname</h2>
        <p>Daftar semua produk stok opname</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    @foreach ([
            'id' => 'ID',
            'produks_id' => 'Id Produk',
            'stok_sys' => 'Stok Sistem',
            'stok_nyata' => 'Stok Nyata',
            'satuan' => 'Satuan Stok',
            'selisih' => 'Selisih',
            'tgl_opname' => 'Tanggal Opname',
            'deskripsi' => 'Keterangan',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ] as $column => $label)
                        <th>
                            <a
                                href="{{ route('opnames.index', [
                                    'sort_by' => $column,
                                    'sort_order' => $sortBy == $column && $sortOrder == 'asc' ? 'desc' : 'asc',
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
                        <td>{{ $d->id }}</td>
                        <td>{{ $d->produks->nama ?? '-' }}</td>
                        <td>{{ $d->stok_sys }}</td>
                        <td>{{ $d->stok_nyata }}</td>
                        <td>{{ $d->satuan->nama ?? '-' }}</td>
                        <td>{{ $d->selisih }}</td>
                        <td>{{ $d->tgl_opname }}</td>
                        <td>{{ $d->deskripsi }}</td>
                        <td>{{ $d->created_at }}</td>
                        <td>{{ $d->updated_at }}</td>
                        <td>
                            <a class="btn btn-warning" href="{{ route('opnames.edit', $d->id) }}">Edit</a>
                            <form method="POST" action="{{ route('opnames.destroy', $d->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete" class="btn btn-danger"
                                    onclick="return confirm('Are you sure to delete {{ $d->id }} - {{ $d->produks->nama }} - {{ $d->tgl_opname }} ?');">
                            </form>
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
