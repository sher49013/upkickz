<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

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
use Illuminate\Support\Facades\Auth;

Auth::routes();


Route::group([ 'middleware' => 'auth'], function () {
    Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');
});

//Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');
