<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/mont-flow', function () {
    return Inertia::render('MontFlow');
});