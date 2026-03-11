<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\DistributorController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\NotabeliController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\NotajualController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfilapotekController;
use App\Http\Controllers\RacikanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukopnameController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsAdminOrApoteker;


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (FULL ACCESS)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', IsAdmin::class])->group(function () {

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('registerUser');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    Route::resource('users', UserController::class);
    Route::resource('distributors', DistributorController::class);
    Route::resource('notabelis', NotabeliController::class);
    Route::resource('satuans', SatuanController::class);
    Route::resource('produks', ProdukController::class);
    Route::resource('racikans', RacikanController::class);
    Route::resource('gudangs', GudangController::class);
    Route::resource('profilapoteks', ProfilapotekController::class);
    Route::resource('forecasts', ForecastController::class);

    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/profile', [UserController::class, 'detail'])->name('profile');

    Route::get('user/uploadImage/{id}', [UserController::class, 'uploadImage']);
    Route::post('user/simpanImage', [UserController::class, 'simpanImage']);

    // Route::get('/distributor', [DistributorController::class, 'index'])->name('distributor');
    // Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan');
    // Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    // Route::get('/racikan', [RacikanController::class, 'index'])->name('racikan');
    // Route::get('/gudang', [GudangController::class, 'index'])->name('gudang');

    Route::get('/forecasts', [ForecastController::class, 'index'])->name('forecast');
    Route::post('/forecast/sales_post', [ForecastController::class, 'sales_post'])->name('forecasts.sales_post');
    Route::get('/forecast/forecasted/{id}', [ForecastController::class, 'forecast'])->name('forecasts.forecast');

    // Route::get('produk/terimaBatch/{id}', [ProdukController::class, 'terimaBatch'])->name('produks.terimaBatch');
    // Route::put('produk/updateTerimaBatch/{id}', [ProdukController::class, 'updateTerimaBatch'])->name('produks.updateTerimaBatch');
    // Route::get('produk/editBatch/{id}', [ProdukController::class, 'editBatch'])->name('produks.editBatch');
    // Route::put('produk/updateBatch/{id}', [ProdukController::class, 'updateBatch'])->name('produks.updateBatch');
    // Route::delete('produk/destroyBatch/{id}', [ProdukController::class, 'destroyBatch'])->name('produks.destroyBatch');
    // Route::delete('produk/destroyTerima/{id}', [ProdukController::class, 'destroyTerima'])->name('produks.destroyTerima');
    // Route::get('produk/daftarTerima', [ProdukController::class, 'daftarTerima'])->name('produks.daftarTerima');

    // Route::get('produk/uploadImage/{id}', [ProdukController::class, 'uploadImage']);
    // Route::post('produk/simpanImage', [ProdukController::class, 'simpanImage']);

    // Route::get('produk/batch/{id}', [ProdukController::class, 'batch'])->name('produks.batch');
    // Route::get('produk/batch/{id}/print', [ProdukController::class, 'print'])->name('produks.print');

    // Route::post('/notabelis/cart', [NotabeliController::class, 'addToCart'])->name('notabelis.cart');
    // Route::delete('/notabelis/cart/delete/{id}', [NotabeliController::class, 'deleteFromCart'])->name('notabeliscart.delete');
    // Route::post('/notabelis/beliProdukBaru', [NotabeliController::class, 'beliProdukBaru'])->name('notabelis.beliProdukBaru');
    // Route::get('/notabelis/{id}/print', [NotabeliController::class, 'print'])->name('notabelis.print');

    // Route::get('transaksi/report/reportPenjualan', [NotajualController::class, 'report'])->name('notajuals.report');
    // Route::get('transaksi/report/reportPenjualan/csv', [NotajualController::class, 'reportCsv'])->name('notajuals.csv');

    // Route::get('transaksi/report/reportPembelian', [NotabeliController::class, 'report'])->name('notabelis.report');
    // Route::get('transaksi/report/reportPembelian/csv', [NotabeliController::class, 'reportCsv'])->name('notabelis.csv');

    Route::get('profilapotek/uploadImage/{id}', [ProfilapotekController::class, 'uploadImage']);
    Route::post('profilapotek/simpanImage', [ProfilapotekController::class, 'simpanImage']);
});


/*
|--------------------------------------------------------------------------
| APOTEKER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', IsAdminOrApoteker::class])->group(function () {

    Route::resource('distributors', DistributorController::class);
    Route::resource('notabelis', NotabeliController::class);
    Route::resource('satuans', SatuanController::class);
    Route::resource('produks', ProdukController::class);
    Route::resource('racikans', RacikanController::class);
    Route::resource('gudangs', GudangController::class);
    Route::resource('opnames', ProdukopnameController::class);

    Route::get('/distributor', [DistributorController::class, 'index'])->name('distributor');
    Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::get('/racikan', [RacikanController::class, 'index'])->name('racikan');
    Route::get('/gudang', [GudangController::class, 'index'])->name('gudang');
    Route::get('/opname', [ProdukopnameController::class, 'index'])->name('opname');

    Route::get('produk/terimaBatch/{id}', [ProdukController::class, 'terimaBatch'])->name('produks.terimaBatch');
    Route::put('produk/updateTerimaBatch/{id}', [ProdukController::class, 'updateTerimaBatch'])->name('produks.updateTerimaBatch');
    Route::get('produk/editBatch/{id}', [ProdukController::class, 'editBatch'])->name('produks.editBatch');
    Route::put('produk/updateBatch/{id}', [ProdukController::class, 'updateBatch'])->name('produks.updateBatch');
    Route::delete('produk/destroyBatch/{id}', [ProdukController::class, 'destroyBatch'])->name('produks.destroyBatch');
    Route::delete('produk/destroyTerima/{id}', [ProdukController::class, 'destroyTerima'])->name('produks.destroyTerima');
    Route::get('produk/daftarTerima', [ProdukController::class, 'daftarTerima'])->name('produks.daftarTerima');
    Route::get('produk/daftarKadaluarsa', [ProdukController::class, 'daftarKadaluarsa'])->name('produks.daftarKadaluarsa');

    Route::get('produk/report/reportKadaluarsa', [ProdukController::class, 'reportKadaluarsa'])->name('produks.reportKadaluarsa');
    Route::get('produk/report/reportKadaluarsa/csv', [ProdukController::class, 'reportCsvKadaluarsa'])->name('produks.csvKadaluarsa');
    Route::get('produk/printKadaluarsa/{id}', [ProdukController::class, 'printKadaluarsa'])->name('produks.printKadaluarsa');

    Route::get('produk/uploadImage/{id}', [ProdukController::class, 'uploadImage']);
    Route::post('produk/simpanImage', [ProdukController::class, 'simpanImage']);

    Route::get('produk/batch/{id}', [ProdukController::class, 'batch'])->name('produks.batch');
    Route::get('produk/batch/{id}/print', [ProdukController::class, 'print'])->name('produks.print');

    Route::get('racikan/daftarNarkotika', [RacikanController::class, 'daftarNarkotika'])->name('racikans.daftarNarkotika');
    Route::get('racikan/report/reportNarkotika', [RacikanController::class, 'reportNarkotika'])->name('racikans.reportNarkotika');
    Route::get('racikan/report/reportNarkotika/csv', [RacikanController::class, 'reportCsvNarkotika'])->name('racikans.CsvNarkotika');
    Route::get('racikan/printNarkotika/{id}', [RacikanController::class, 'printNarkotika'])->name('racikans.printNarkotika');

    Route::post('/notabelis/cart', [NotabeliController::class, 'addToCart'])->name('notabelis.cart');
    Route::delete('/notabelis/cart/delete/{id}', [NotabeliController::class, 'deleteFromCart'])->name('notabeliscart.delete');
    Route::post('/notabelis/beliProdukBaru', [NotabeliController::class, 'beliProdukBaru'])->name('notabelis.beliProdukBaru');
    Route::get('/notabelis/{id}/print', [NotabeliController::class, 'print'])->name('notabelis.print');

    Route::get('transaksi/report/reportPenjualan', [NotajualController::class, 'report'])->name('notajuals.report');
    Route::get('transaksi/report/reportPenjualan/csv', [NotajualController::class, 'reportCsv'])->name('notajuals.csv');

    Route::get('transaksi/report/reportPembelian', [NotabeliController::class, 'report'])->name('notabelis.report');
    Route::get('transaksi/report/reportPembelian/csv', [NotabeliController::class, 'reportCsv'])->name('notabelis.csv');

    Route::get('/transaksi', function () {
        return view('transaksi.tipe');
    })->name('transaksi');
});


/*
|--------------------------------------------------------------------------
| ALL AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', [ProdukController::class, 'homeProduk'])->name('homeProduk');

    Route::resource('notajuals', NotajualController::class);

    Route::get('racikan/komposisi/{id}', [RacikanController::class, 'komposisi'])->name('racikans.komposisi');
    Route::get('racikan/notaracikan', [RacikanController::class, 'notaRacikan'])->name('racikans.notaRacikan');
    Route::post('racikan/jualracikan/{id}', [RacikanController::class, 'jualRacikan'])->name('racikans.jualRacikan');

    Route::delete(
        'racikan/destroyKomposisi/{racikans_id}/{produks_id}',
        [RacikanController::class, 'destroyKomposisi']
    )->name('racikans.destroyKomposisi');

    Route::get('racikan/uploadImage/{id}', [RacikanController::class, 'uploadImage']);
    Route::post('racikan/simpanImage', [RacikanController::class, 'simpanImage']);

    Route::post('/notajuals/cart', [NotajualController::class, 'addToCart'])->name('notajuals.cart');
    Route::delete('/notajuals/cart/delete/{id}', [NotajualController::class, 'deleteFromCart'])->name('notajualscart.delete');
    Route::get('/notajuals/{id}/print', [NotajualController::class, 'print'])->name('notajuals.print');
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [ProdukController::class, 'welcomeProduk'])->name('welcome');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show'); //ini harus berada di paling bawah agar tidak tabrakan dengan route yang identik
Route::get('/profilapotek', [ProfilapotekController::class, 'index'])->name('profilapotek');

Auth::routes(['register' => false]);

Route::post('/logout', function () {
    Session::forget('cart');
    Auth::logout();
    return redirect()->route('welcome');
})->name('logout');
