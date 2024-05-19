<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('urlshortener');
});

Auth::routes();

Route::post('/auth/{provider}/callback', [LoginController::class,'handleCallback']);

Route::get('/u/{any}',[UrlShortenerController::class,'handle']);

Route::middleware(['checkSession'])->group(function () {
    Route::get('/dashboard',[UrlShortenerController::class,'dashboard']);
    Route::delete('/url/delete/{id}',[UrlShortenerController::class,'delete']);
    Route::post('/url/shorten',[UrlShortenerController::class,'store']);
});



