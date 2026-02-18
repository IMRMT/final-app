<?php

namespace App\Http\Controllers;

use App\Models\Notajual;
use App\Models\Notajualracikan;
use App\Models\Notajualproduk;
use App\Models\Produk;
use App\Models\Produkbatches;
use App\Models\Racikan;
use App\Models\Racikanproduk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RacikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        $query = \App\Models\Racikan::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('deskripsi', 'like', "%$search%")
                    ->orWhere('aturan_pakai', 'like', "%$search%");
            });
        }

        $query->orderBy($sortBy, $sortOrder);

        $datas = $query->paginate(10);

        return view('racikan.index', compact('datas', 'search', 'sortBy', 'sortOrder'));
    }

    public function notaRacikan(Request $request)
    {
        $query = Notajualracikan::query()
            ->select(
                'notajuals_has_racikans.*',
                'racikans.id as racikans_id',
                'racikans.nama as nama_racikan',
                'users.nama as nama_pegawai'
            )
            ->join('notajuals', 'notajuals_has_racikans.notajuals_id', '=', 'notajuals.id')
            ->join('racikans', 'notajuals_has_racikans.racikans_id', '=', 'racikans.id')
            ->join('users', 'notajuals.pegawai_id', '=', 'users.id');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('racikans.nama', 'LIKE', "%$search%")
                    ->orWhere('users.nama', 'LIKE', "%$search%")
                    ->orWhere('notajuals_has_racikans.quantity', 'LIKE', "%$search%")
                    ->orWhere('notajuals_has_racikans.subtotal', 'LIKE', "%$search%")
                    ->orWhere('notajuals_has_racikans.created_at', 'LIKE', "%$search%")
                    ->orWhere('notajuals_has_racikans.updated_at', 'LIKE', "%$search%");
            });
        }

        $sortBy = $request->get('sort_by', 'notajuals_id');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'nama_racikan':
                $query->orderBy('racikans.nama', $sortOrder);
                break;
            case 'nama_pegawai':
                $query->orderBy('users.nama', $sortOrder);
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
                break;
        }

        $datas = $query->paginate(10);

        return view('transaksi.daftarPeracikan', [
            'datas' => $datas,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'search' => $search
        ]);
    }

    public function komposisi(Request $request)
    {
        $id = $request->id;
        $data = Racikan::findOrFail($id);
        $query = Racikanproduk::query()
            ->select(
                'racikanproduks.*',
                'produks.id as produks_id',
                'produks.nama as nama_produk',
                'racikans.id as racikans_id',
                'racikans.nama as nama_racikan',
            )
            ->join('produks', 'racikanproduks.produks_id', '=', 'produks.id')
            ->join('racikans', 'racikanproduks.racikans_id', '=', 'racikans.id')
            ->where('racikanproduks.racikans_id', $id);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('racikanproduks.racikans_id', 'LIKE', "%$search%")
                    ->orWhere('racikanproduks.produks_id', 'LIKE', "%$search%")
                    ->orWhere('produks.nama', 'LIKE', "%$search%")
                    ->orWhere('racikans.nama', 'LIKE', "%$search%")
                    ->orWhere('racikanproduks.quantity', 'LIKE', "%$search%")
                    ->orWhere('racikanproduks.created_at', 'LIKE', "%$search%")
                    ->orWhere('racikanproduks.updated_at', 'LIKE', "%$search%");
            });
        }

        $sortBy = $request->get('sort_by', 'racikanproduks.racikans_id');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'id_racikan':
                $query->orderBy('racikanproduks.racikans_id', $sortOrder);
                break;
            case 'id_produk':
                $query->orderBy('racikanproduks.produks_id', $sortOrder);
                break;
            case 'nama_produk':
                $query->orderBy('produks.nama', $sortOrder);
                break;
            case 'nama_racikan':
                $query->orderBy('racikans.nama', $sortOrder); //bisa ga kepakai di view
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
                break;
        }

        $datas = $query->paginate(8);
        // $a = GeneralModel::generateIDBatch(1);


        return view('racikan.komposisi', [
            'datas' => $datas,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'komposisi' => $data,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $racikans = Racikan::all();
        $produks = Produk::whereIn('golongan', ['terbatas', 'keras'])->get();
        return view('racikan.create', ['racikans' => $racikans, 'produks' => $produks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
            'nama_dokter' => 'required',
            'nama_pasien' => 'required',
            'aturan_pakai' => 'required',
            'biaya_embalase' => 'required',
            'produks_id' => 'required|array',
            'quantity' => 'required|array',
        ]);

        // Create Racikan
        $racikan = new Racikan();
        $racikan->nama = $request->get('nama');
        $racikan->deskripsi = $request->get('deskripsi');
        $racikan->nama_dokter = $request->get('nama_dokter');
        $racikan->nama_pasien = $request->get('nama_pasien');
        $racikan->aturan_pakai = $request->get('aturan_pakai');
        $racikan->biaya_embalase = $request->get('biaya_embalase');
        $racikan->save();

        // Save compositions
        foreach ($request->produks_id as $index => $produk_id) {
            Racikanproduk::create([
                'racikans_id' => $racikan->id,
                'produks_id' => $produk_id,
                'quantity' => $request->quantity[$index],
            ]);
        }

        return redirect('racikans')->with('status', 'The new data has been inserted');
    }

    public function jualRacikan($id, Request $request)
    {
        $pegawaiId = $request->input('pegawai_id');
        if (!$pegawaiId) {
            return back()->with('error', 'Pegawai ID is required.');
        }

        // 1. Get racikan and its komposisi
        $racikan = Racikan::findOrFail($id);

        // === CHECK: bukti_resep must not be null ===
        if (!$racikan->bukti_resep) {
            return back()->with('error', 'Racikan tidak dapat dijual karena bukti resep belum diunggah.');
        }

        $komposisi = $racikan->racikanproduks()->with('produk')->get();

        // 2. PRE-CHECK: Ensure all ingredient stock is sufficient
        foreach ($komposisi as $komponen) {
            $produk = $komponen->produk;
            $requiredQty = $komponen->quantity;

            $availableStock = Produkbatches::where('produks_id', $produk->id)
                ->where('stok', '>', 0)
                ->where('status', 'tersedia')
                ->whereDate('tgl_kadaluarsa', '>', now())
                ->sum('stok');

            if ($availableStock < $requiredQty) {
                return back()->with('error', "Stok tidak mencukupi untuk produk: {$produk->nama}. Dibutuhkan: {$requiredQty}, tersedia: {$availableStock}");
            }
        }

        // 3. Create NotaJual only if all stocks are sufficient
        $notajual = NotaJual::create([
            'pegawai_id' => $pegawaiId,
        ]);

        $totalHarga = 0;

        foreach ($komposisi as $komponen) {
            $produk = $komponen->produk;
            $sisa = $komponen->quantity;

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
            'quantity' => 1,
            'subtotal' => $totalHarga,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('racikans.index')->with('success', 'Racikan berhasil dijual.');
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
    public function edit($id)
    {
        $data = Racikan::findOrFail($id);
        $produks = Produk::all();

        // Load existing komposisi (racikanproduks)
        $komposisi = $data->racikanproduks()->with('produk')->get();

        return view('racikan.edit', [
            'datas' => $data,
            'produks' => $produks,
            'komposisi' => $komposisi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $racikan = Racikan::findOrFail($id);
    //     $racikan->nama = $request->get('nama');
    //     $racikan->deskripsi = $request->get('deskripsi');
    //     $racikan->aturan_pakai = $request->get('aturan_pakai');
    //     $racikan->save();

    //     $produks_ids = $request->input('produks_id', []);
    //     $quantities = $request->input('quantity', []);
    //     $processed_ids = [];

    //     for ($i = 0; $i < count($produks_ids); $i++) {
    //         $produks_id = $produks_ids[$i];
    //         $quantity = $quantities[$i];

    //         // Avoid duplicate entries in form input
    //         if (in_array($produks_id, $processed_ids)) {
    //             continue;
    //         }
    //         $processed_ids[] = $produks_id;

    //         $existing = Racikanproduk::where('racikans_id', $racikan->id)
    //             ->where('produks_id', $produks_id)
    //             ->first();

    //         if ($existing) {
    //             $existing->quantity = $quantity;
    //             $existing->save();
    //         } else {
    //             Racikanproduk::create([
    //                 'racikans_id' => $racikan->id,
    //                 'produks_id' => $produks_id,
    //                 'quantity' => $quantity,
    //             ]);
    //         }
    //     }

    //     // HARD DELETE entries that are no longer in the form
    //     Racikanproduk::where('racikans_id', $racikan->id)
    //         ->whereNotIn('produks_id', $processed_ids)
    //         ->delete();

    //     return redirect('racikans')->with('status', 'Racikan dan komposisi berhasil diperbarui');
    // }

    public function update(Request $request, $id)
    {
        $racikan = Racikan::findOrFail($id);
        $racikan->nama = $request->get('nama');
        $racikan->deskripsi = $request->get('deskripsi');
        $racikan->aturan_pakai = $request->get('aturan_pakai');
        $racikan->biaya_embalase = $request->get('biaya_embalase');
        $racikan->save();

        $produks_ids = $request->input('produks_id', []);
        $quantities = $request->input('quantity', []);

        $processed_produks_ids = [];

        for ($i = 0; $i < count($produks_ids); $i++) {
            $produks_id = $produks_ids[$i];
            $quantity = $quantities[$i];

            // Track processed produks_id for deletion filtering later
            $processed_produks_ids[] = $produks_id;

            // Check if the record exists even if soft-deleted
            $existing = DB::table('racikanproduks')
                ->where('racikans_id', $racikan->id)
                ->where('produks_id', $produks_id)
                ->first();

            if ($existing) {
                // Restore soft deleted by setting deleted_at = null and update quantity
                DB::table('racikanproduks')
                    ->where('racikans_id', $racikan->id)
                    ->where('produks_id', $produks_id)
                    ->update([
                        'deleted_at' => null,
                        'quantity' => $quantity,
                        'updated_at' => now(),
                    ]);
            } else {
                // Create new record
                DB::table('racikanproduks')->insert([
                    'racikans_id' => $racikan->id,
                    'produks_id' => $produks_id,
                    'quantity' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Soft delete komposisi that are no longer in the form input
        DB::table('racikanproduks')
            ->where('racikans_id', $racikan->id)
            ->whereNotIn('produks_id', $processed_produks_ids)
            ->update(['deleted_at' => now(), 'updated_at' => now()]);

        return redirect('racikans')->with('status', 'Racikan dan komposisi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            //if no contraint error, then delete data. Redirect to index after it.
            $deletedData = Racikan::find($id);
            $deletedData->delete();
            return redirect('racikans')->with('status', 'Horray ! Your data is successfully deleted !');
        } catch (\PDOException $ex) {
            // Failed to delete data, then show exception message
            $msg = "Failed to delete data ! Make sure there is no related data before deleting it";
            return redirect('racikans')->with('status', $msg);
        }
    }

    public function destroyKomposisi($racikans_id, $produks_id)
    {
        // First, check if the entry exists
        $komposisi = DB::table('racikanproduks')
            ->where('racikans_id', $racikans_id)
            ->where('produks_id', $produks_id)
            ->first();

        if (!$komposisi) {
            return redirect()->route('racikans.komposisi', ['id' => $racikans_id])
                ->with('status', 'Composition not found.');
        }

        try {
            // Soft delete manually (since Eloquent can't handle composite keys well)
            DB::table('racikanproduks')
                ->where('racikans_id', $racikans_id)
                ->where('produks_id', $produks_id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now(),
                ]);

            return redirect()->route('racikans.komposisi', ['id' => $racikans_id])
                ->with('status', 'Composition successfully deleted!');
        } catch (\Throwable $ex) {
            return redirect()->route('racikans.komposisi', ['id' => $racikans_id])
                ->with('status', 'Failed to delete! Make sure there are no related records.');
        }
    }

    public function uploadImage(Request $request)
    {
        $id = $request->id;
        $racikan = Racikan::find($id);
        return view('racikan.formUploadImage', compact('racikan'));
    }

    public function simpanImage(Request $request)
    {
        $file = $request->file("file_photo");
        $folder = 'resep_image';
        $filename = time() . "_" . $file->getClientOriginalName();
        $file->move($folder, $filename);
        $racikan = Racikan::find($request->id);
        $racikan->bukti_resep = $filename;
        $racikan->save();
        return redirect()->route('racikans.index')->with('status', 'photo terupload');
    }
}
