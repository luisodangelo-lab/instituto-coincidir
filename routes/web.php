<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\OtpService;
use App\Models\User;
use App\Http\Controllers\Auth\FirstAccessController;
use App\Http\Controllers\Auth\DniLoginController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Auth\PasswordResetOtpController;


Route::get('/', function () {
    return view('welcome');
});

/**
 * DEV ONLY: test OTP generator
 * (Opcional: desactivarlo cuando ya no lo uses)
 */
Route::get('/dev/otp-test', function (OtpService $otp) {
    abort_unless(app()->environment('local'), 404);


    $user = User::first() ?? User::create([
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => bcrypt('secret1234'),
        'dni' => '12345678',
        'role' => 'alumno',
        'account_state' => 'active',
    ]);

    $res = $otp->createChallenge($user->id, 'first_access', request()->ip());

    return response()->json([
        'user_id' => $user->id,
        'challenge_id' => $res['challenge']->id,
        'code_dev' => $res['code_plain'],
        'expires_at' => $res['challenge']->expires_at,
    ]);
});

// Primer acceso (DNI -> OTP -> contraseña)
Route::get('/first-access', [FirstAccessController::class, 'show'])->name('first_access.show');
Route::post('/first-access', [FirstAccessController::class, 'sendOtp'])->name('first_access.send');
Route::get('/first-access/verify', [FirstAccessController::class, 'showVerify'])->name('first_access.verify');
Route::post('/first-access/verify', [FirstAccessController::class, 'verify'])->name('first_access.verify.post');
Route::get('/first-access/password', [FirstAccessController::class, 'showPassword'])->name('first_access.password');
Route::post('/first-access/password', [FirstAccessController::class, 'setPassword'])->name('first_access.password.post');

// Login normal (DNI + contraseña)
Route::get('/login', [DniLoginController::class, 'show'])->name('login');
Route::post('/login', [DniLoginController::class, 'login'])->name('login.post');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
Route::get('/login', [DniLoginController::class, 'show'])->name('login');
Route::post('/login', [DniLoginController::class, 'login'])->name('login.post');

Route::get('/admin/test', function () {
    return 'OK admin';
})->middleware(['auth', 'role:admin']);

Route::prefix('admin')
    ->middleware(['auth', 'role:admin,staff_l1,administrativo'])
    ->group(function () {
        Route::get('/users/create', [UserAdminController::class, 'create'])->name('admin.users.create');
        Route::post('/users/create', [UserAdminController::class, 'store'])->name('admin.users.store');
    });

Route::get('/reset-password', [PasswordResetOtpController::class, 'show'])->name('reset.show');
Route::post('/reset-password', [PasswordResetOtpController::class, 'sendOtp'])->name('reset.send');
Route::get('/reset-password/verify', [PasswordResetOtpController::class, 'showVerify'])->name('reset.verify');
Route::post('/reset-password/verify', [PasswordResetOtpController::class, 'verify'])->name('reset.verify.post');
Route::get('/reset-password/new', [PasswordResetOtpController::class, 'showPassword'])->name('reset.password');
Route::post('/reset-password/new', [PasswordResetOtpController::class, 'setPassword'])->name('reset.password.post');
