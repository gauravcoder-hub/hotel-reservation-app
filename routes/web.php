<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;

Route::get('/', [HotelController::class, 'index'])->name('hotel.index');
Route::post('/book', [HotelController::class, 'book'])->name('book.rooms');
Route::post('/random', [HotelController::class, 'random'])->name('hotel.random');
Route::post('/reset', [HotelController::class, 'reset'])->name('hotel.reset');
Route::get('/ping', function () {
    return 'Laravel is working';
});


