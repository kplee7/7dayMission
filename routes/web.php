<?php

use App\Http\Controllers\DevAccountController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/posts/new', [HomeController::class, 'newPosts'])->name('home.posts.new');
});

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// 로컬 개발용 계정 선택 화면 (컨트롤러가 local 환경 밖에서는 404를 던진다)
Route::get('/auth/accounts', [DevAccountController::class, 'index'])->name('dev.accounts');
Route::post('/auth/accounts/{user}', [DevAccountController::class, 'login'])->name('dev.login');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');
