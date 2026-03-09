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

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Ubah Stok Opname</h1>

    <form method="POST" action="{{ route('opnames.update', $datas->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="produks">Nama Produk</label>
            <select class="form-control" name="produks">
                @foreach ($produks as $p)
                    <option value="{{ $p->id }}"{{ $p->id == $datas->produks_id ? 'selected' : '' }}>
                        {{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="satuans">Satuan Produk</label>
            <select class="form-control" name="satuans">
                @foreach ($satuans as $s)
                    <option value="{{ $s->id }}"{{ $s->id == $datas->satuans_id ? 'selected' : '' }}>
                        {{ $s->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="stok_sys">Stok Sistem</label>
            <input type="number" class="form-control" name="stok_sys" aria-describedby="nameHelp"
                placeholder="Masukkan Stok Sistem" value="{{$datas->stok_sys}}">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="stok_nyata">Stok Nyata</label>
            <input type="number" class="form-control" name="stok_nyata" aria-describedby="nameHelp"
                placeholder="Masukkan Stok Nyata" value="{{$datas->stok_nyata}}">
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <div class="form-group">
            <label for="tgl_opname">Tanggal Opname</label>
            <input type="date" class="form-control" name="tgl_opname" aria-describedby="dateHelp"
                value="{{ $datas->tgl_opname }}">
            <small id="dateHelp" class="form-text text-muted">Pilih tanggal opname produk.</small>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi Produk</label>
            <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $datas->deskripsi) }}</textarea>
            <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('produks.index') }}"
            class="btn btn-primary bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
    </form>
@endsection
