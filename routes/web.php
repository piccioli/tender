<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/nova/dashboards/main');
    }
    
    return view('static-home');
});
