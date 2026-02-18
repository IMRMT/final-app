@extends('layout.conquer')
@section('title')
@section('content')

<h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Ubah Produk</h1>

<form method="POST" action="{{route('produks.update', $datas->id)}}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="nama">Nama Produk</label>
        <input type="text" class="form-control" name="nama" aria-describedby="nameHelp"
            placeholder="Masukkan Nama Produk" value="{{$datas->nama}}">
        <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
    </div>
    <div class="form-group">
        <label for="sellingprice">Harga Produk</label>
        <input type="number" class="form-control" name="sellingprice" aria-describedby="nameHelp"
            placeholder="Masukkan Harga Produk" value="{{$datas->sellingprice}}">
        <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
    </div>
    <div class="form-group">
        <label for="golongan">Golongan Produk</label>
        <select class="form-control" name="golongan" aria-describedby="nameHelp">
            <option value="bebas" {{ $datas->golongan == 'bebas' ? 'selected' : '' }}>Bebas</option>
            <option value="terbatas" {{ $datas->golongan == 'terbatas' ? 'selected' : '' }}>Terbatas</option>
            <option value="keras" {{ $datas->golongan == 'keras' ? 'selected' : '' }}>Keras</option>
        </select>
        <small id="nameHelp" class="form-text text-muted">Mohon pilih input yang diinginkan.</small>
    </div>
    <div class="form-group">
        <label for="deskripsi">Deskripsi Produk</label>
        <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $datas->deskripsi) }}</textarea>
        <small id="nameHelp" class="form-text text-muted">Mohon isikan dengan input yang diinginkan.</small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{ route('produks.index') }}" class="btn btn-primary bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
</form>
@endsection