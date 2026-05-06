<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AccessRequestController;
use App\Http\Controllers\User\TestController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AccessManagementController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\User\KraepelinController;

// ==================== PENGGUNA (Publik) ====================
Route::get('/', [AccessRequestController::class, 'landing'])->name('home');
Route::get('/daftar', [AccessRequestController::class, 'index'])->name('register');
Route::post('/request-access', [AccessRequestController::class, 'store'])->name('request.store');
Route::get('/enter-code', [AccessRequestController::class, 'enterCode'])->name('enter.code');
Route::post('/verify-code', [AccessRequestController::class, 'verifyCode'])->name('verify.code');
Route::get('/panduan', [AccessRequestController::class, 'panduan'])->name('panduan');

// Tes (dilindungi session)
Route::middleware('test.session')->group(function () {
    Route::get('/test', [TestController::class, 'index'])->name('test.index');
    Route::post('/test/answer', [TestController::class, 'saveAnswer'])->name('test.answer');
    Route::get('/test/result', [TestController::class, 'result'])->name('test.result');
    Route::get('/kraepelin', [KraepelinController::class, 'index'])->name('kraepelin.index');
    Route::post('/kraepelin/save', [KraepelinController::class, 'saveColumn'])->name('kraepelin.save');
});
Route::get('/test/finish', [TestController::class, 'finish'])->name('test.finish');
Route::get('/kraepelin/finish', [KraepelinController::class, 'finish'])->name('kraepelin.finish');
// ==================== ADMIN ====================
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    

    // Panel Admin (dilindungi auth)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Akses
        Route::get('/requests', [AccessManagementController::class, 'index'])->name('requests.index');
        Route::post('/requests/{id}/approve', [AccessManagementController::class, 'approve'])->name('requests.approve');
        Route::post('/requests/{id}/reject', [AccessManagementController::class, 'reject'])->name('requests.reject');
        Route::post('/requests/bulk-action', [AccessManagementController::class, 'bulkAction'])->name('requests.bulk');
        Route::delete('/requests/{id}', [AccessManagementController::class, 'destroy'])->name('requests.destroy');

        // Hasil Tes
        Route::get('/results', [ResultController::class, 'index'])->name('results.index');
        Route::get('/results/{id}', [ResultController::class, 'show'])->name('results.show');
        Route::get('/results/{id}/pdf', [ResultController::class, 'exportPdf'])->name('results.pdf');

        // ganti password admin
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});