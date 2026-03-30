<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\BackOfficeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredCars = Car::query()
        ->where('is_featured', true)
        ->orderByRaw('featured_order IS NULL')
        ->orderBy('featured_order')
        ->orderByDesc('id')
        ->limit(3)
        ->get();

    if ($featuredCars->count() < 3) {
        $fallbackCars = Car::query()
            ->whereNotIn('id', $featuredCars->pluck('id'))
            ->orderByDesc('id')
            ->limit(3 - $featuredCars->count())
            ->get();

        $featuredCars = $featuredCars->concat($fallbackCars);
    }

    return view('home', [
        'featuredCars' => $featuredCars->values(),
    ]);
});

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/team', function () {
    return view('team');
});

Route::get('/faq', function () {
    return view('faq');
});

Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

// Manual auth routes (login, register, password reset, email verification)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    // Password Reset Routes
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('definicoes', [AccountSettingsController::class, 'edit'])->name('account.settings');
    Route::put('definicoes', [AccountSettingsController::class, 'update'])->name('account.settings.update');
    Route::view('favoritos', 'account.favorites')->name('account.favorites');
    Route::get('back/dashboard', function () {
        abort_unless((int) Auth::user()->tipo_id === 1, 403);

        return view('back.dashboard');
    })->name('back.dashboard');

    Route::get('back/cars/highlights', [CarController::class, 'highlights'])->name('back.cars.highlights');
    Route::put('back/cars/highlights', [CarController::class, 'updateHighlights'])->name('back.cars.highlights.update');

    Route::resource('back/cars', CarController::class)
        ->names('back.cars')
        ->except(['show']);
    Route::get('back/cars/{car}', [CarController::class, 'show'])->name('back.cars.show');

    Route::get('back/users', [BackOfficeController::class, 'usersIndex'])->name('back.users.index');
    Route::put('back/users/{user}/tipo', [BackOfficeController::class, 'usersUpdateTipo'])->name('back.users.tipo.update');
});

// Email Verification Routes
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify')->middleware('signed');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
