<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth','verified'])->name('dashboard');

Route::group(['middleware' => ['auth','verified']], function () {
    Route::post('/reservations/list', [ReservationController::class,'getReservationList']);
});

require __DIR__.'/auth.php';
