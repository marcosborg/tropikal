<?php

use App\Http\Controllers\{QuoteRequestController,SiteController}; use Illuminate\Support\Facades\Route;
Route::get('/',[SiteController::class,'home'])->name('home'); Route::get('/catalogo',[SiteController::class,'catalog'])->name('catalog'); Route::get('/categorias/{category:slug}',[SiteController::class,'category'])->name('category'); Route::get('/produtos/{product:slug}',[SiteController::class,'product'])->name('product'); Route::post('/pedir-orcamento',[QuoteRequestController::class,'store'])->middleware('throttle:5,1')->name('quote.store');
Route::redirect('/paineis-fotovoltaicos.html','/categorias/sistemas-solares',301); Route::redirect('/bombas-de-calor.html','/categorias/bombas-de-calor',301); Route::redirect('/ar-condicionado.html','/categorias/ar-condicionado',301); Route::redirect('/domotica-iot.html','/categorias/domotica',301);
Route::get('/{slug}.html',[SiteController::class,'page'])->name('page');
