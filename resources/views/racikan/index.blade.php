@extends('layout.conquer')
@section('title')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-success">{{ session('error') }}</div>
    @endif

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Daftar Racikan</h1>

    <a href="{{ route('racikans.create') }}" class="btn btn-primary mb-3">Create New Racikan</a>

    <div class="container">
        <!-- Search Bar -->
        <form method="GET" action="{{ route('racikans.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Racikan..."
                    value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary mb-3">Search</button>
                </div>
            </div>
        </form>

        <p>Daftar racikan yang tercatat</p>

        <table class="table table-striped">
            <thead>
                <tr>
                    @foreach ([
            'id' => 'ID',
            'nama' => 'Nama Racikan',
            'biaya_embalase' => 'Biaya Embalase',
            'deskripsi' => 'Deskripsi',
            'nama_dokter' => 'Dokter',
            'nama_pasien' => 'Pasien',
            'aturan_pakai' => 'Aturan Pakai',
        ] as $column => $label)
                        <th>
                            <a
                                href="{{ route('racikans.index', ['sort_by' => $column, 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                                {{ $label }}
                                @if ($sortBy == $column)
                                    {{ $sortOrder == 'asc' ? '▲' : '▼' }}
                                @endif
                            </a>
                        </th>
                    @endforeach
                    <th>Bukti Resep</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datas as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->biaya_embalase }}</td>
                        <td>{{ $d->deskripsi }}</td>
                        <td>{{ $d->nama_dokter }}</td>
                        <td>{{ $d->nama_pasien }}</td>
                        <td>{{ $d->aturan_pakai }}</td>
                        <td>
                            <img height="100px" src="{{ asset('/resep_image/' . $d->bukti_resep) }}" alt="Resep Image" /><br>
                            <a href="{{ url('racikan/uploadImage/' . $d->id) }}" class="btn btn-xs btn-default">Upload</a>
                        </td>
                        <td>
                            <form action="{{ route('racikans.jualRacikan', $d->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <input type="hidden" name="pegawai_id" value="{{ auth()->user()->id }}">
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Yakin ingin menjual racikan {{ $d->id }} - {{ $d->nama }}?');">Jual</button>
                            </form>
                            <a href="{{ route('racikans.komposisi', $d->id) }}">Komposisi</a>
                            <a class="btn btn-warning" href="{{ route('racikans.edit', $d->id) }}">Edit</a>
                            <form method="POST" action="{{ route('racikans.destroy', $d->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete" class="btn btn-danger"
                                    onclick="return confirm('Are you sure to delete {{ $d->id }} - {{ $d->nama }} ?');">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $datas->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
