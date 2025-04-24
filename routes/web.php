<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/{lang?}/{slug?}', [PageController::class, 'show'])->name(name: 'page.show');
