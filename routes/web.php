<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get("trucks/{truck}/subunit", [\App\Http\Controllers\TruckController::class, "subunit"])->name("trucks.subunit");
Route::post("trucks/{truck}/substitute", [\App\Http\Controllers\TruckController::class, "substitute"])->name("trucks.substitute");
Route::delete('/trucks/{truck}/subunits/{truckSubunit}', [\App\Http\Controllers\TruckController::class, "destroySub"])->name('trucks.destroySub');


Route::resource('trucks', \App\Http\Controllers\TruckController::class);
Route::resource('truckSubunits',\App\Http\Controllers\TruckSubunitController::class);


