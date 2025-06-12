<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SimpleMessageController;

Route::middleware('auth:siteuser')->group(function () {
    // Mesaj gönder (her iki rolde de aynı)
    Route::post('/message/send', [SimpleMessageController::class, 'send'])
         ->name('message.send');

    // İlan sahibi için konuşma kanalları listesi
    Route::get('/messages', [SimpleMessageController::class, 'threads'])
         ->name('message.threads');

    // Belirli bir kullanıcıyla konuşma thread’i
    Route::get('/messages/{user}', [SimpleMessageController::class, 'showThread'])
         ->name('message.thread');
});

Route::get('/{lang?}/{slug?}', [PageController::class, 'show'])->name(name: 'page.show');