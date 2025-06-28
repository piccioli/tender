<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/nova/welcome-page');
    }
    
    return view('static-home');
});
