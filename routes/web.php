<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', function () {
    return view('welcome');
});

/* Option livewire */
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(env('LIVEWIRE_UPDATE_PATH'), $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(env('LIVEWIRE_JAVASCRIPT_PATH'), $handle);
});
