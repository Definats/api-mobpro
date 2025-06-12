<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('show');
Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('store');

Route::POST('/peminjaman/edit/{id}', [PeminjamanController::class, 'update'])->name('update');

Route::delete('/peminjaman/delete/{id}', [PeminjamanController::class, 'destroy'])->name('destroy');