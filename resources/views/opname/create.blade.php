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

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Buat Stok Baru</h1>

    <form method="POST" action="{{ route('opnames.store') }}">
        @csrf
        <div class="form-group">
            <label for="produks">Nama Produk</label>
            <select class="form-control" name="produks">
                @foreach ($produks as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="satuans">Satuan Produk</label>
            <select class="form-control" name="satuans">
                @foreach ($satuans as $s)
                    <option value="{{ $s->id }}">
                        {{ $s->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="stok_nyata">Stok Nyata</label>
            <input type="number" class="form-control" name="stok_nyata" aria-describedby="nameHelp"
                placeholder="Masukkan Stok Nyata">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="tgl_opname">Tanggal Opname</label>
            <input type="date" class="form-control" name="tgl_opname" aria-describedby="dateHelp"
                value="{{ old('tgl_opname') }}">
            <small id="dateHelp" class="form-text text-muted">Pilih tanggal opname produk.</small>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi Produk</label>
            <textarea class="form-control" name="deskripsi" rows="4" placeholder="Masukkan Deskripsi Produk"></textarea>
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('opnames.index') }}" class="btn btn-secondary ml-2">Batal</a>
    </form>
@endsection
