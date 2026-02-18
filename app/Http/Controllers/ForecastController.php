<?php

namespace App\Http\Controllers;

use App\Models\Notajual;
use App\Models\Notajualproduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForecastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produks = DB::table('produks')
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();

        return view('forecast.index',  compact('produks'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    public function sales_post(Request $request)
    {
        $type = $request->input('type', 'sma');
        $id = $request->input('produk_id');

        $salesData = DB::select("
            SELECT DATE_FORMAT(njp.created_at, '%Y-%m') AS period, SUM(njp.quantity) AS total_quantity
            FROM notajuals_has_produks njp
            JOIN produkbatches pb ON njp.produkbatches_id = pb.id
            WHERE pb.produks_id = ? AND njp.deleted_at IS NULL
            GROUP BY period
            ORDER BY period", [$id]);

        $salesArray = array_map(function ($row) use ($type) {
            return [
                'period' => $row->period,
                'total_quantity' => (int) $row->total_quantity,
                'type' => $type
            ];
        }, $salesData);

        file_put_contents(public_path("forecasting/sales_data.json"), json_encode($salesArray));

        return redirect()->route('forecast')->with([
            'status' => 'Sales data JSON saved!',
            'selected_id' => $id
        ]);
    }

    public function forecast($id)
    {
        $python = 'python';
        $script = public_path('forecasting/arima_forecast.py');
        $salesDataPath = public_path('forecasting/sales_data.json');
        $forecastPath = public_path('forecasting/arima_forecast.json');

        // Execute Python script and pass paths as arguments
        exec("$python \"$script\" $salesDataPath $forecastPath 2>&1", $output, $status);

        if ($status !== 0) {
            dd($output);
            return back()->with('error', 'Python execution failed: ' . implode("\n", $output));
        }

        $forecast = json_decode(file_get_contents($forecastPath), true);

        // Read MAPE from the output
        $mape = null;
        foreach ($output as $line) {
            if (strpos($line, 'MAPE:') !== false) {
                preg_match('/MAPE: ([\d\.]+)/', $line, $matches);
                $mape = $matches[1] ?? null;
            }
        }

        $actualSales = DB::table('notajuals_has_produks')
            ->selectRaw("DATE_FORMAT(notajuals_has_produks.created_at, '%Y-%m') as month, SUM(quantity) as total")
            ->join('produkbatches' ,'notajuals_has_produks.produkbatches_id', 'produkbatches.id')
            ->where('produkbatches.produks_id', $id)
            ->whereNull("notajuals_has_produks.deleted_at")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->reverse(); // to make it chronological

        $historical = [];
        foreach ($actualSales as $row) {
            $historical[$row->month] = (int) $row->total;
        }

        $produks = DB::table('produks')->select('id', 'nama')->get();

        return view('forecast.index', compact('forecast', 'produks', 'mape', 'historical'))->with('produk_id', $id);
    }
}
