<?php

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
Route::get('module.select', [\App\Http\Controllers\ModuleController::class, 'select'])->name('module.select');
Route::get('app.select', [\App\Http\Controllers\AppController::class, 'select'])->name('app.select');
Route::delete('feature.delete', [\App\Http\Controllers\FeatureController::class, 'delete'])->name('feature.delete');
Route::delete('module.delete', [\App\Http\Controllers\ModuleController::class, 'delete'])->name('module.delete');
Route::delete('app.delete', [\App\Http\Controllers\AppController::class, 'delete'])->name('app.delete');
Route::resource('/feature', \App\Http\Controllers\FeatureController::class);
Route::resource('/module', \App\Http\Controllers\ModuleController::class);
Route::get('app/print/{id}', [\App\Http\Controllers\AppController::class, 'print'])->name('app.print');
Route::resource('/app', \App\Http\Controllers\AppController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
