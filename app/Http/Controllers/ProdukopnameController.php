<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Produkopnames;
use App\Models\Produkbatches;
use App\Models\Satuan;
use Illuminate\Http\Request;

class ProdukopnameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'tgl_opname');
        $sortOrder = $request->get('sort_order', 'desc');
        $search = $request->get('search');

        $query = Produkopnames::with(['produks', 'satuan']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('stok_sys', 'LIKE', "%$search%")
                    ->orWhere('stok_nyata', 'LIKE', "%$search%")
                    ->orWhere('selisih', 'LIKE', "%$search%")
                    ->orWhere('tgl_opname', 'LIKE', "%$search%")
                    ->orWhere('deskripsi', 'LIKE', "%$search%")
                    ->orWhereHas('produks', fn($q) => $q->where('nama', 'LIKE', "%$search%"))
                    ->orWhereHas('satuan', fn($q) => $q->where('nama', 'LIKE', "%$search%"));
            });
        }

        if (in_array($sortBy, ['stok_sys', 'stok_nyata', 'selisih', 'tgl_opname', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $opname = $query->paginate(8);

        return view('opname.index', [
            'datas' => $opname,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::all();
        $satuans = Satuan::all();
        return view('opname.create', ['produks' => $produks, 'satuans' => $satuans]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stok_nyata' => 'required',
            'tgl_opname' => 'required',
            'deskripsi' => 'nullable',
            'satuans_id' => $request->get('satuans'),
            'produks_id' => $request->get('produks'),
        ]); //ini memberitahu bahwa kolom name itu perlu, agar tidak null

        $produks_id = $request->produks;

        $stok_sys = Produkbatches::where('produks_id', $produks_id)
            ->sum('stok');
        $opname = new Produkopnames();
        $opname->produks_id = $produks_id;
        $opname->satuans_id = $request->satuans;
        $opname->stok_sys = $stok_sys; //didapat dari sum stok semua produkbatches
        $opname->stok_nyata = $request->stok_nyata;
        $opname->selisih = $request->stok_nyata - $stok_sys;
        $opname->tgl_opname = $request->tgl_opname;
        $opname->deskripsi = $request->deskripsi;
        $opname->save();


        // Type::create($request->all());
        return redirect('opnames')->with('status', 'The new data has been inserted');
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
        // $objType = $type;
        // dd($type);
        $data = Produkopnames::find($id);
        $produks = Produk::all();
        $satuans = Satuan::all();
        // dd($data);
        // echo'masuk form edit';
        return view('opname.edit', [
            'datas' => $data,
            'produks' => $produks,
            'satuans' => $satuans
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Produkopnames::find($id);
        $stok_sys = Produkbatches::where('produks_id', $request->produks)->sum('stok');
        $data->produks_id = $request->get('produks');
        $data->satuans_id = $request->get('satuans');
        $data->stok_sys = $stok_sys;
        $data->stok_nyata = $request->get('stok_nyata');
        $data->selisih = $data->stok_sys - $data->stok_nyata;
        $data->tgl_opname = $request->get('tgl_opname');
        $data->deskripsi = $request->get('deskripsi');
        $data->save();

        // Type::create($request->all());
        return redirect('opnames')->with('status', 'The new data has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            //if no contraint error, then delete data. Redirect to index after it.
            $deletedData = Produkopnames::find($id);
            $deletedData->delete();
            return redirect('opnames')->with('status', 'Horray ! Your data is successfully deleted !');
        } catch (\PDOException $ex) {
            // Failed to delete data, then show exception message
            $msg = "Failed to delete data ! Make sure there is no related data before deleting it";
            return redirect('opnames')->with('status', $msg);
        }
    }
}
