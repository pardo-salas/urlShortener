<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;

Route::get('/', function () {
    return view('urlshortener');
});

Auth::routes();

Route::get('/u/{any}',[UrlShortenerController::class,'handle']);

Route::get('/dashboard',[UrlShortenerController::class,'dashboard']);

Route::post('/url/shorten',[UrlShortenerController::class,'store']);

Route::delete('/url/delete/{id}',[UrlShortenerController::class,'delete']);

Route::put('url/edit/{url}',[UrlShortenerController::class,'edit']);