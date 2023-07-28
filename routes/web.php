<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return redirect()->route('login');
});


Route::middleware('auth')->group(callback: function (){
    Route::get('/proprietarios', function () {
        return Inertia::render('Owners', [
            'brands' => \App\Models\Brand::get()
        ]);
    })->name('proprietarios');

    Route::get('/marcas', function () {
        return Inertia::render('Brand');
    })->name('marcas');

    Route::get('/carros', function () {
        return Inertia::render('Cars');
    })->name('carros');

    Route::controller(OwnerController::class)->group(function (){
        Route::get('/owner', 'index')->name('owner.all');
        Route::post('/owner', 'store')->name('owner.store');
        Route::patch('/owner/{owner}', 'update')->name('owner.update');
    });

    Route::controller(BrandController::class)->group(function (){
        Route::get('/brand', 'index')->name('brand.all');
        Route::post('/brand', 'store')->name('brand.store');
        Route::patch('/brand/{brand}', 'update')->name('brand.update');
    });

    Route::controller(CarController::class)->group(function (){
        Route::get('/car', 'index')->name('car.all');
        Route::post('/car', 'store')->name('car.store');
        Route::patch('/car/{car}', 'update')->name('car.update');
    });

    Route::controller(ProfileController::class)->group(function (){
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

});

require __DIR__.'/auth.php';
