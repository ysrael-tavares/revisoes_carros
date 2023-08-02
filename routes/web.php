<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RevisionController;
use App\Models\Brand;
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
            'brands' => Brand::get()
        ]);
    })->name('proprietarios');

    Route::get('/marcas', function () {
        return Inertia::render('Brand');
    })->name('marcas');

    Route::get('/carros', function () {
        return Inertia::render('Cars', [
            'brands' => Brand::get()
        ]);
    })->name('carros');

    Route::get('/revisoes', function () {
        return Inertia::render('Revisions');
    })->name('revisoes');

    Route::controller(OwnerController::class)->group(function (){
        Route::get('/owner', 'index')->name('owner.all');
        Route::post('/owner', 'store')->name('owner.store');
        Route::patch('/owner/{owner}', 'update')->name('owner.update');
        Route::delete('/owner/{owner}', 'destroy')->name('owner.destroy');

        Route::get('/owner_by_sex', 'by_sex')->name('owner.all_by_sex');
    });

    Route::controller(BrandController::class)->group(function (){
        Route::get('/brand', 'index')->name('brand.all');
        Route::post('/brand', 'store')->name('brand.store');
        Route::patch('/brand/{brand}', 'update')->name('brand.update');
        Route::delete('/brand/{brand}', 'destroy')->name('brand.destroy');
    });

    Route::controller(CarController::class)->group(function (){
        Route::get('/car', 'index')->name('car.all');
        Route::post('/car', 'store')->name('car.store');
        Route::patch('/car/{car}', 'update')->name('car.update');
        Route::delete('/car/{car}', 'destroy')->name('car.destroy');

        Route::get('/show_by_owners', 'showByOwners')->name('show.by_owners');
        Route::get('/count_by_gender', 'countByGender')->name('show.count_by_gender');
    });

    Route::controller(RevisionController::class)->group(function (){
        Route::get('/revision', 'index')->name('revision.all');
        Route::post('/revision', 'store')->name('revision.store');
        Route::patch('/revision/{revision}', 'update')->name('revision.update');
        Route::delete('/revision/{revision}', 'destroy')->name('revision.destroy');
    });

});

require __DIR__.'/auth.php';
