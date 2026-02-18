<?php

namespace App\Http\Controllers;

// use App\Models\GeneralModel;
use App\Models\Notajual;
use App\Models\Notajualproduk;
use App\Models\Produk;
use App\Models\Produkbatches;
use App\Models\Racikan;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotajualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $query = NotajualProduk::query()
        //     ->select('notajuals_has_produks.*')
        //     ->join('notajuals', 'notajuals_has_produks.notajuals_id', '=', 'notajuals.id')
        //     ->join('users', 'notajuals.pegawai_id', '=', 'users.id')
        //     ->join('produkbatches', function ($join) {
        //         $join->on('notajuals_has_produks.produk_batches_produks_id', '=', 'produkbatches.produks_id')
        //             ->on('notajuals_has_produks.produk_batches_distributors_id', '=', 'produkbatches.distributors_id');
        //     })
        //     ->join('produks', 'produkbatches.produks_id', '=', 'produks.id')
        //     ->join('distributors', 'produkbatches.distributors_id', '=', 'distributors.id');
        // 

        $query = NotajualProduk::query()
            ->select(
                'notajuals_has_produks.*',
                'produks.id as produks_id',
                'produks.nama as nama_produk',
                'distributors.id as distributors_id',
                'distributors.nama as nama_distributor',
                'satuans.nama as nama_satuan',
                'users.nama as nama_pegawai'
            )
            ->join('notajuals', 'notajuals_has_produks.notajuals_id', '=', 'notajuals.id')
            ->join('users', 'notajuals.pegawai_id', '=', 'users.id')
            ->join('produkbatches', 'notajuals_has_produks.produkbatches_id', '=', 'produkbatches.id')
            ->join('produks', 'produkbatches.produks_id', '=', 'produks.id')
            ->join('distributors', 'produkbatches.distributors_id', '=', 'distributors.id')
            ->join('satuans', 'produkbatches.satuans_id', '=', 'satuans.id');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('produkbatches.id', 'LIKE', "%$search%")
                    ->orWhere('produks.nama', 'LIKE', "%$search%")
                    ->orWhere('distributors.nama', 'LIKE', "%$search%")
                    ->orWhere('satuans.nama', 'LIKE', "%$search%")
                    ->orWhere('users.nama', 'LIKE', "%$search%")
                    ->orWhere('notajuals_has_produks.quantity', 'LIKE', "%$search%")
                    ->orWhere('notajuals_has_produks.subtotal', 'LIKE', "%$search%")
                    ->orWhere('notajuals_has_produks.created_at', 'LIKE', "%$search%")
                    ->orWhere('notajuals_has_produks.updated_at', 'LIKE', "%$search%");
            });
        }

        $sortBy = $request->get('sort_by', 'notajuals_id');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'id_batch':
                $query->orderBy('produkbatches.id', $sortOrder);
                break;
            case 'nama_pegawai':
                $query->orderBy('users.nama', $sortOrder);
                break;
            case 'nama_produk':
                $query->orderBy('produks.nama', $sortOrder);
                break;
            case 'nama_dist':
                $query->orderBy('distributors.nama', $sortOrder);
                break;
            case 'satuan':
                $query->orderBy('satuans.nama', $sortOrder);
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
                break;
        }

        $datas = $query->paginate(10)->appends([
            'search' => $search,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ]);
        // $a = GeneralModel::generateIDBatch(1);


        return view('transaksi.daftarPenjualan', [
            'datas' => $datas,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'search' => $search
        ]);
    }

    public function report(Request $request)
    {
        $filter = $request->get('filter', 'day');

        // Base query: eager load related produk info
        $query = Notajualproduk::with('notajual', 'produkbatches.produks');

        // Grouping and filtering by created_at according to filter
        switch ($filter) {
            case 'week':
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            case 'day':
            default:
                $query->whereDate('created_at', now()->toDateString());
        }

        $sales = $query->get();

        // Calculate total sales (sum of subtotal)
        $total = $sales->sum('subtotal');

        return view('transaksi.reportPenjualan', compact('sales', 'total', 'filter'));
    }

    public function reportCsv(Request $request)
    {
        $filter = $request->get('filter', 'day');

        $query = Notajualproduk::with('notajual', 'produkbatches.produks');

        switch ($filter) {
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            case 'day':
            default:
                $query->whereDate('created_at', now()->toDateString());
        }

        $sales = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan_penjualan.csv"',
        ];

        $callback = function () use ($sales) {
            // Open PHP output stream
            $file = fopen('php://output', 'w');

            // BOM to ensure Excel reads UTF-8 properly
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Column headers (change if you want more user-friendly names)
            fputcsv($file, ['Nota ID', 'ID Produk', 'Nama Produk', 'Quantity', 'Subtotal'], ';');

            // Data rows
            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->notajual->id ?? '-',
                    $sale->produkbatches->produks_id ?? '-',
                    $sale->produkbatches->produks->nama ?? '-',
                    $sale->quantity,
                    number_format($sale->subtotal, 0, ',', '.') // Formatted as currency
                ], ';'); // Use semicolon as separator for Excel
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(Request $request)
    // {
    //     $search = $request->input('search');
    //     $cart = session('cart', []);

    //     $notajuals = Notajual::all();

    //     // Get all racikans with their ingredients and calculate total price
    //     $racikans = DB::table('racikans')
    //         ->select('racikans.id', 'racikans.nama', 'racikans.biaya_embalase')
    //         ->get();

    //     foreach ($racikans as $racikan) {
    //         $details = DB::table('racikanproduks')
    //             ->join('produks', 'racikanproduks.produks_id', '=', 'produks.id')
    //             ->where('racikanproduks.racikans_id', $racikan->id)
    //             ->select('produks.sellingprice', 'racikanproduks.quantity')
    //             ->get();

    //         $totalKomponen = 0;
    //         foreach ($details as $d) {
    //             $totalKomponen += $d->sellingprice * $d->quantity;
    //         }

    //         $racikan->harga = $racikan->biaya_embalase + $totalKomponen;
    //         $racikan->image = null;
    //         $racikan->satuan_nama = '-';
    //         $racikan->stok = 1; // default
    //         $racikan->sellingprice = $racikan->harga;
    //         $racikan->tgl_kadaluarsa = null;
    //         $racikan->distributors_id = null;
    //         $racikan->is_racikan = true;
    //     }

    //     // Regular product logic
    //     $produksQuery = DB::table('produkbatches')
    //         ->join('produks', 'produkbatches.produks_id', '=', 'produks.id')
    //         ->join('satuans', 'produkbatches.satuans_id', '=', 'satuans.id')
    //         ->where('produkbatches.status', '=', 'tersedia')
    //         ->where('produkbatches.stok', '>', 0)
    //         ->whereDate('produkbatches.tgl_kadaluarsa', '>', Carbon::now())
    //         ->whereNotNull('produkbatches.tgl_datang')
    //         ->select(
    //             'produks.id',
    //             'produks.nama',
    //             'produks.image',
    //             'satuans.nama as satuan_nama',
    //             DB::raw('SUM(produkbatches.stok) as stok'),
    //             DB::raw('MIN(produks.sellingprice) as sellingprice'),
    //             DB::raw('MIN(produkbatches.tgl_kadaluarsa) as tgl_kadaluarsa'),
    //             DB::raw('MIN(produkbatches.distributors_id) as distributors_id')
    //         )
    //         ->groupBy('produks.id', 'produks.nama', 'produks.image', 'satuan_nama');

    //     if ($search) {
    //         $produksQuery->where('produks.nama', 'like', '%' . $search . '%');
    //     }

    //     $produks = $produksQuery->get();

    //     // Merge regular products and racikan
    //     $produks = $produks->merge($racikans);

    //     $users = User::all();

    //     return view('transaksi.jualProduk', [
    //         'data' => $notajuals,
    //         'prod' => $produks,
    //         'user' => $users,
    //         'search' => $search,
    //         'cart' => $cart
    //     ]);
    // }

    public function create(Request $request)
    {
        // session()->forget('cart');
        $search = $request->input('search');
        $cart = session('cart', []);

        // Get all racikan products
        // $racikans = DB::table('racikans')
        //     ->select('racikans.id', 'racikans.nama', 'racikans.biaya_embalase')
        //     ->get();

        // foreach ($racikans as $racikan) {
        //     // Get racikan details: ingredients and their quantities
        //     $details = DB::table('racikanproduks')
        //         ->join('produkbatches', 'racikanproduks.produks_id', '=', 'produkbatches.produks_id')
        //         ->where('racikanproduks.racikans_id', $racikan->id)
        //         ->where('produkbatches.status', 'tersedia')
        //         ->where('produkbatches.stok', '>', 0)
        //         ->whereDate('produkbatches.tgl_kadaluarsa', '>', Carbon::now())
        //         ->select('racikanproduks.produks_id', 'racikanproduks.quantity', DB::raw('SUM(produkbatches.stok) as total_stok'))
        //         ->groupBy('racikanproduks.produks_id', 'racikanproduks.quantity')
        //         ->get();

        //     // Calculate racikan price
        //     $totalKomponen = 0;
        //     foreach ($details as $d) {
        //         // To get selling price, fetch from produks table separately
        //         $sellingPrice = DB::table('produks')->where('id', $d->produks_id)->value('sellingprice') ?? 0;
        //         $totalKomponen += $sellingPrice * $d->quantity;
        //     }

        //     $racikan->harga = $racikan->biaya_embalase + $totalKomponen;
        //     $racikan->image = null;
        //     $racikan->satuan_nama = '-';
        //     $racikan->sellingprice = $racikan->harga;
        //     $racikan->tgl_kadaluarsa = null;
        //     $racikan->distributors_id = null;
        //     $racikan->is_racikan = true;

        //     // Calculate minimum stock available based on ingredients
        //     $minAvailable = PHP_INT_MAX;

        //     foreach ($details as $ingredient) {
        //         $required = $ingredient->quantity;
        //         $totalAvailable = $ingredient->total_stok ?? 0;

        //         if ($required > 0) {
        //             $maxMakeable = intdiv($totalAvailable, $required);
        //             $minAvailable = min($minAvailable, $maxMakeable);
        //         }
        //     }

        //     $racikan->stok = $minAvailable > 0 ? $minAvailable : 0;
        // }

        // Regular products query (unchanged)
        $produksQuery = DB::table('produkbatches')
            ->join('produks', 'produkbatches.produks_id', '=', 'produks.id')
            ->join('satuans', 'produkbatches.satuans_id', '=', 'satuans.id')
            ->where('produkbatches.status', '=', 'tersedia')
            ->where('produkbatches.stok', '>', 0)
            ->where(function ($query) {
                $query->whereDate('produkbatches.tgl_kadaluarsa', '>', Carbon::now())
                    ->orWhereNull('produkbatches.tgl_kadaluarsa');
            })
            ->whereNotNull('produkbatches.tgl_datang')
            ->select(
                'produks.id',
                'produks.nama',
                'produks.image',
                'satuans.nama as satuan_nama',
                DB::raw('SUM(produkbatches.stok) as stok'),
                DB::raw('MIN(produks.sellingprice) as sellingprice'),
                DB::raw('MIN(produkbatches.tgl_kadaluarsa) as tgl_kadaluarsa'),
                DB::raw('MIN(produkbatches.distributors_id) as distributors_id')
            )
            ->groupBy('produks.id', 'produks.nama', 'produks.image', 'satuan_nama');

        if ($search) {
            $produksQuery->where('produks.nama', 'like', '%' . $search . '%');
        }

        $produks = $produksQuery->get();

        // Merge regular and racikan products
        // $merged = $produks->merge($racikans);

        $page = request()->get('page', 1);
        $perPage = 6;
        $offset = ($page - 1) * $perPage;
        $paginated = new LengthAwarePaginator(
            $produks->slice($offset, $perPage)->values(),  // use only $produks
            $produks->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('transaksi.jualProduk', [
            'prod' => $paginated,
            'search' => $search,
            'cart' => $cart
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'pegawai_id' => 'required',
    //     ]);

    //     $cart = session('cart', []);

    //     if (empty($cart)) {
    //         return redirect()->back()->withErrors('Keranjang kosong.');
    //     }

    //     // Create main sale record
    //     $nota = Notajual::create([
    //         'pegawai_id' => $request->pegawai_id,
    //     ]);

    //     // Save each item from cart
    //     foreach ($cart as $id => $item) {
    //         $produkId = $item['id'];
    //         $quantityToSell = $item['quantity'];

    //         $batches = DB::table('produkbatches')
    //             ->join('produks', 'produkbatches.produks_id', '=', 'produks.id')
    //             ->select(
    //                 'produks.id as prod_id',
    //                 'produks.sellingprice as sellingprice',
    //                 'produkbatches.id as id',
    //                 'produkbatches.stok as stok',
    //                 'produkbatches.distributors_id as distributors_id',
    //                 'produkbatches.tgl_kadaluarsa as tgl_kadaluarsa',
    //             )
    //             ->where('produks_id', $produkId)
    //             ->where('status', 'tersedia')
    //             ->whereDate('tgl_kadaluarsa', '>', now())
    //             ->orderBy('tgl_kadaluarsa', 'asc')
    //             ->get();

    //         foreach ($batches as $batch) {
    //             if ($quantityToSell <= 0) break;

    //             $qtyFromThisBatch = min($quantityToSell, $batch->stok);
    //             if ($qtyFromThisBatch <= 0) continue;

    //             Notajualproduk::create([
    //                 'notajuals_id' => $nota->id,
    //                 'produkbatches_id' => $batch->id,
    //                 'quantity' => $qtyFromThisBatch,
    //                 'unitprice' => $batch->sellingprice,
    //                 'subtotal' => $qtyFromThisBatch * $batch->sellingprice,
    //             ]);

    //             // Reduce stock
    //             DB::table('produkbatches')
    //                 ->where('produks_id', $batch->prod_id)
    //                 ->where('distributors_id', $batch->distributors_id)
    //                 ->whereDate('tgl_kadaluarsa', $batch->tgl_kadaluarsa)
    //                 ->decrement('stok', $qtyFromThisBatch);

    //             $quantityToSell -= $qtyFromThisBatch;
    //         }

    //         if ($quantityToSell > 0) {
    //             // Not enough stock, optionally roll back or notify
    //             return redirect()->back()->withErrors("Stok untuk produk {$item['nama']} tidak mencukupi.");
    //         }

    //         // Clear cart
    //     }
    //     session()->forget('cart');

    //     return redirect()->route('transaksi')->with('status', 'Penjualan Tercatat');
    // }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $pegawaiId = $request->input('pegawai_id');

            $notajual = Notajual::create([
                'pegawai_id' => $pegawaiId,
            ]);

            $ids = $request->input('id');
            $quantities = $request->input('quantity');
            $isRacikanFlags = $request->input('is_racikan', []);

            // dd($isRacikanFlags, $ids, $quantities);

            foreach ($ids as $i => $produkId) {
                $jumlah = $quantities[$i];
                $isRacikan = $isRacikanFlags[$i] == '1';

                if ($isRacikan) {
                    // ==== RACIKAN ====
                    $racikan = Racikan::with('produks')->findOrFail($produkId);
                    // dd($racikan->toArray());
                    $totalHarga = 0;

                    foreach ($racikan->produks as $produk) {
                        $totalQty = $produk->pivot->quantity * $jumlah;
                        $sisa = $totalQty;

                        $batches = Produkbatches::where('produks_id', $produk->id)
                            ->where('stok', '>', 0)
                            ->where('status', 'tersedia')
                            ->whereDate('tgl_kadaluarsa', '>', now())
                            ->orderBy('tgl_kadaluarsa')
                            ->get();

                        foreach ($batches as $batch) {
                            if ($sisa <= 0) break;

                            $terjual = min($sisa, $batch->stok);
                            $batch->decrement('stok', $terjual);

                            DB::table('notajuals_has_produks')->insert([
                                'notajuals_id' => $notajual->id,
                                'produkbatches_id' => $batch->id,
                                'quantity' => $terjual,
                                'subtotal' => $terjual * $produk->sellingprice,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            $totalHarga += $terjual * $produk->sellingprice;
                            $sisa -= $terjual;
                        }
                    }

                    $totalHarga += $racikan->biaya_embalase;

                    DB::table('notajuals_has_racikans')->insert([
                        'notajuals_id' => $notajual->id,
                        'racikans_id' => $racikan->id,
                        'quantity' => $jumlah,
                        'subtotal' => $totalHarga,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    // ==== REGULAR PRODUK ====
                    $sisa = $jumlah;

                    $batches = Produkbatches::where('produks_id', $produkId)
                        ->where('stok', '>', 0)
                        ->where('status', 'tersedia')
                        ->where(function ($query) {
                            $query->whereDate('tgl_kadaluarsa', '>', now())
                                ->orWhereNull('tgl_kadaluarsa');
                        })
                        // ->whereDate('tgl_kadaluarsa', '>', now())
                        // ->OrwhereDate('tgl_kadaluarsa', '=', null)
                        ->orderBy('tgl_kadaluarsa')
                        ->get();

                    $produk = \App\Models\Produk::findOrFail($produkId);

                    foreach ($batches as $batch) {
                        if ($sisa <= 0) break;

                        $terjual = min($sisa, $batch->stok);
                        $batch->decrement('stok', $terjual);

                        DB::table('notajuals_has_produks')->insert([
                            'notajuals_id' => $notajual->id,
                            'produkbatches_id' => $batch->id,
                            'quantity' => $terjual,
                            'subtotal' => $terjual * $produk->sellingprice,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $sisa -= $terjual;
                    }
                }
            }

            // ==== RACIKAN CART ====
            $racikanCart = session('racikan_cart', []);
            foreach ($racikanCart as $item) {
                $racikanId = $item['racikan_id'];
                $jumlah = $item['jumlah'];
                $racikan = Racikan::with('produks')->findOrFail($racikanId);
                $totalHarga = 0;

                foreach ($racikan->produks as $produk) {
                    $totalQty = $produk->pivot->quantity * $jumlah;
                    $sisa = $totalQty;

                    $batches = Produkbatches::where('produks_id', $produk->id)
                        ->where('stok', '>', 0)
                        ->where('status', 'tersedia')
                        ->whereDate('tgl_kadaluarsa', '>', now())
                        ->orderBy('tgl_kadaluarsa')
                        ->get();

                    foreach ($batches as $batch) {
                        if ($sisa <= 0) break;

                        $terjual = min($sisa, $batch->stok);
                        $batch->decrement('stok', $terjual);

                        DB::table('notajuals_has_produks')->insert([
                            'notajuals_id' => $notajual->id,
                            'produkbatches_id' => $batch->id,
                            'quantity' => $terjual,
                            'subtotal' => $terjual * $produk->sellingprice,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $totalHarga += $terjual * $produk->sellingprice;
                        $sisa -= $terjual;
                    }
                }

                $totalHarga += $racikan->biaya_embalase;

                DB::table('notajuals_has_racikans')->insert([
                    'notajuals_id' => $notajual->id,
                    'racikans_id' => $racikanId,
                    'quantity' => $jumlah,
                    'subtotal' => $totalHarga,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            session()->forget(['cart', 'racikan_cart']);
            DB::commit();

            return redirect()->route('notajuals.index')->with('success', 'Nota jual berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deletedData = Notajual::findOrFail($id);

            // Delete all related nota jual produks
            $deletedData->notaJualProduks()->delete();

            // Delete the notajual itself
            $deletedData->delete();
            return redirect('notajuals')->with('status', 'Horray ! Your data is successfully deleted !');
        } catch (\PDOException $ex) {
            // Failed to delete data, then show exception message
            $msg = "Failed to delete data ! Make sure there is no related data before deleting it";
            return redirect('notajuals')->with('status', $msg);
        }
    }

    // public function addToCart(Request $request)
    // {
    //     $cart = session()->get('cart', []);

    //     $id = $request->input('produkbatches_id');
    //     $tgl_kadaluarsa = $request->input('tgl_kadaluarsa');
    //     $quantity = (int) $request->input('quantity');

    //     $produk = Produk::find($id);

    //     if (!$produk || $quantity < 1) {
    //         return redirect()->back()->with('error', 'Produk tidak valid atau jumlah tidak boleh nol.');
    //     }

    //     $cartKey = $id . '_' . $tgl_kadaluarsa;
    //     // Just use the produk ID as the key
    //     $cart[$cartKey] = [
    //         'id' => $id,
    //         'nama' => $produk->nama,
    //         'distributors_id' => $request->input('distributors_id'),
    //         'tgl_kadaluarsa' => $tgl_kadaluarsa,
    //         'sellingprice' => $request->input('sellingprice'),
    //         'satuan' => $request->input('satuan'),
    //         'quantity' => $quantity,
    //     ];

    //     session(['cart' => $cart]);

    //     return redirect()->route('notajuals.create')->with('success', 'Produk ditambahkan ke keranjang.');
    // }
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $id = $request->input('id');
        $isRacikan = $request->input('is_racikan') == true || $request->input('is_racikan') == 'true';

        // Use unique key for racikan or normal product
        $key = $isRacikan ? 'racikan_' . $id : $id;

        $cart[$key] = [
            'id' => $id,
            'nama' => $request->input('nama'),
            'satuan' => $request->input('satuan'),
            'sellingprice' => $request->input('sellingprice'),
            'stok' => $request->input('stok'),
            'tgl_kadaluarsa' => $request->input('tgl_kadaluarsa') ?? 0,
            'distributors_id' => $request->input('distributors_id'),
            'quantity' => $request->input('quantity'),
            'is_racikan' => $isRacikan,
        ];

        session()->put('cart', $cart);

        return redirect()->route('notajuals.create')->with('success', 'Item added to cart.');
    }

    public function deleteFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('status', 'Produk telah dibuang dari Cart');
    }

    public function print($id)
    {
        $nota = Notajual::with(['user', 'notajualproduks.produkbatches.produks', 'notajualracikans.racikan'])->findOrFail($id);

        // dd($nota);
        return view('transaksi.njPrint', compact('nota'));
    }
}
