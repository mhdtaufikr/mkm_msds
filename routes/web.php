<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;


Route::get('/', [IndexController::class, 'index'])->name('index');;
Route::post('/shop', [IndexController::class, 'storeShop'])->name('shop.store');
Route::post('/document', [IndexController::class, 'storeDocument'])->name('document.store');
Route::delete('/document/{id}', [IndexController::class, 'deleteDocument'])->name('document.delete');
Route::get('/shop/{id}', [IndexController::class, 'show'])->name('shop.show');
// 🔥 route khusus preview PDF
Route::get('/preview/{file}', [IndexController::class, 'preview'])
    ->where('file', '.*')
    ->name('doc.preview');

Route::get('/qr', function (Request $request) {
    return response(
        QrCode::format('svg')->size(200)->generate($request->text),
        200,
        ['Content-Type' => 'image/svg+xml']
    );
});
