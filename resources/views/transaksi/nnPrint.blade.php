@extends('layout.conquer')

@section('title')

@section('content')
    <style>
        @media print {

            .page-sidebar-menu,
            .main-sidebar,
            .navbar,
            .footer,
            .page-sidebar-menu-collapse {
                display: none !important;
            }

            .content-wrapper,
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            body {
                overflow: visible !important;
            }

            button {
                display: none !important;
            }
        }
    </style>

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Detail Narkotika</h1>

    <h2 style="text-align:center;">
        Laporan Penggunaan Obat Narkotika & Psikotropika
    </h2>

    <p>
        Racikan ID : {{ $datas->first()->racikan_id ?? '-' }}
    </p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Racikan ID</th>
                <th>Batch ID</th>
                <th>Nama Obat</th>
                <th>Satuan</th>
                <th>Stok Awal</th>
                <th>Distributor</th>
                <th>Diterima</th>
                <th>Dipakai</th>
                <th>Stok Akhir</th>
                <th>Nama Pasien</th>
                <th>Alamat Pasien</th>
                <th>Nama Dokter</th>
                <th>Alamat Dokter</th>
                <th>Tanggal Ambil</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
    <button onclick="window.print()" class="btn btn-primary mt-3">Print</button>
    </div>

    <style>
        @media print {
            .btn {
                display: none;
            }

            .print-split {
                page-break-before: always;
            }
        }
    </style>
@endsection
