<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RadioBroadcastController;


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('broadcasts', RadioBroadcastController::class);
    Route::post('broadcasts/{broadcast}/approve', [RadioBroadcastController::class, 'approve'])->name('broadcasts.approve');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
