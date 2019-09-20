<?php

/**
 * 1. Retornando a View home
 */
Route::get('/', function (){
    return view('welcome');
})->name('welcome');
