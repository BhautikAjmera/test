<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/',[ProductController::class,'index'])->name('product.index');
Route::post('store/product',[ProductController::class,'store'])->name('product.store');
Route::get('edit/product',[ProductController::class,'edit'])->name('product.edit');