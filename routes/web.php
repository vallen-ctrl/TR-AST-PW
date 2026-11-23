<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name("scholarships.index");

Route::get("/daftar", function(){
    return view("apply");
});

