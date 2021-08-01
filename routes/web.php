<?php

use App\Http\Controllers\TrainController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/ticket-book', [TrainController::class,'show'])->name('ticket-book');
Route::post('/ticket-book', [TrainController::class,'book'])->name('ticket-book');
Route::get('/delete-tickets', [TrainController::class,'delete'])->name('delete-tickets');
