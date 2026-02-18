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

    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Detail Nota Penjualan</h1>

    <div class="container mt-4">
        <h2>Nota Penjualan</h2>
        <p><strong>ID Nota:</strong> {{ $nota->id }}</p>
        <p><strong>Tanggal:</strong> {{ $nota->created_at->format('d M Y') }}</p>
        <p><strong>Pegawai:</strong> {{ $nota->user->nama }}</p>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                @endphp
                @foreach ($nota->notajualproduks as $item)
                    @php
                        $namaProduk = $item->produkbatches->produks->nama ?? '-';
                        $subtotal = $item->subtotal;
                        $grandTotal += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $namaProduk }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp{{ number_format($subtotal / $item->quantity, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                {{-- Racikan Section --}}
                @if ($nota->notajualracikans->count() > 0)
                    @foreach ($nota->notajualracikans as $item)
                        @php
                            $racikan = $item->racikan; // this is the actual Racikan model
                            $biaya = $racikan->biaya_embalase ?? 0;
                            $grandTotal += $biaya;
                        @endphp
                        <tr>
                            <td colspan="3"><em>Biaya Embalase ({{ $racikan->nama ?? 'Racikan' }})</em><br>
                            Aturan Pakai: {{ $racikan->aturan_pakai ?? 'Racikan' }}
                            </td>
                            <td>Rp{{ number_format($biaya, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th>Rp{{ number_format($grandTotal, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <button onclick="window.print()" class="btn btn-primary mt-3">Print Nota</button>
    </div>
@endsection
