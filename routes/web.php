<?php

use App\Http\Controllers\SceneController;
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

Route::controller(SceneController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/fixtures', 'fixtures')->name('fixtures');
    Route::get('/simulation', 'simulation')->name('simulation');
});
