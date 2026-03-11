@extends('layout.conquer')

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

            a {
                display: none !important;
            }
        }
    </style>

    <div class="container">

        <h1>Laporan narkotika dan psikotropika</h1>
        <!-- Table -->
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
            <tfoot>
                <tr>
                    <th colspan="6">Total Pemakaian</th>
                    <th>{{ $total }}</th>
                </tr>
            </tfoot>
        </table>
        <button onclick="window.print()" class="btn btn-primary mt-3">Print Laporan</button>
        <a href="{{ route('racikans.CsvNarkotika', ['filter' => $filter]) }}" class="btn btn-success mt-3">Download CSV</a>
    </div>
@endsection
