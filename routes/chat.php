<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

// Chat routes
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/start', [ChatController::class, 'start'])->name('chat.start');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/mark-read', [ChatController::class, 'markAsRead'])->name('chat.markRead');
    Route::get('/chat/messages/{conversation}', [ChatController::class, 'messages'])->name('chat.messages');
    Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unreadCount');
});
