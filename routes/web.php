<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\OtpService;
use App\Models\User;

use App\Http\Controllers\Auth\FirstAccessController;
use App\Http\Controllers\Auth\DniLoginController;
use App\Http\Controllers\Auth\PasswordResetOtpController;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\FinancePaymentsController;

use App\Http\Controllers\My\MyInstallmentsController;
use App\Http\Controllers\My\MyPaymentsController;

use App\Http\Controllers\Admin\CoursesController;
use App\Http\Controllers\Admin\CohortsController;
use App\Http\Controllers\Admin\EnrollmentsController;

// LECTURA académica (admin, staff_l1, staff_l2, administrativo, docente)
Route::prefix('admin')
  ->middleware(['auth','role:admin,staff_l1,staff_l2,administrativo,docente'])
  ->group(function () {
      Route::get('/enrollments', [EnrollmentsController::class, 'index'])->name('admin.enrollments.index');
      Route::get('/enrollments/{enrollment}', [EnrollmentsController::class, 'show'])->name('admin.enrollments.show');

      Route::get('/courses', [CoursesController::class, 'index'])->name('admin.courses.index');
      Route::get('/courses/{course}/cohorts', [CohortsController::class, 'index'])->name('admin.cohorts.index');
  });

// ESCRITURA académica (admin, staff_l1, administrativo)
Route::prefix('admin')
  ->middleware(['auth','role:admin,staff_l1,administrativo'])
  ->group(function () {
      Route::get('/courses/create', [CoursesController::class, 'create'])->name('admin.courses.create');
      Route::post('/courses/create', [CoursesController::class, 'store'])->name('admin.courses.store');
      Route::get('/courses/{course}/edit', [CoursesController::class, 'edit'])->name('admin.courses.edit');
      Route::post('/courses/{course}/edit', [CoursesController::class, 'update'])->name('admin.courses.update');

      Route::get('/courses/{course}/cohorts/create', [CohortsController::class, 'create'])->name('admin.cohorts.create');
      Route::post('/courses/{course}/cohorts/create', [CohortsController::class, 'store'])->name('admin.cohorts.store');
      Route::get('/courses/{course}/cohorts/{cohort}/edit', [CohortsController::class, 'edit'])->name('admin.cohorts.edit');
      Route::post('/courses/{course}/cohorts/{cohort}/edit', [CohortsController::class, 'update'])->name('admin.cohorts.update');

      Route::get('/enrollments/create', [EnrollmentsController::class, 'create'])->name('admin.enrollments.create');
      Route::post('/enrollments/create', [EnrollmentsController::class, 'store'])->name('admin.enrollments.store');
  });


Route::get('/', function () {
    return view('welcome');
});

/**
 * DEV ONLY: test OTP generator
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

// Perfil (avatar)
Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth')
    ->name('profile.show');

Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
    ->middleware('auth')
    ->name('profile.avatar');

Route::post('/profile/avatar/remove', [ProfileController::class, 'removeAvatar'])
    ->middleware('auth')
    ->name('profile.avatar.remove');

// Área autenticada
Route::middleware(['auth'])->group(function () {

    // Alumno: ver cuotas + subir pago con comprobante
    Route::get('/my/installments', [MyInstallmentsController::class, 'index'])->name('my.installments');
    Route::get('/my/payments/new', [MyPaymentsController::class, 'create'])->name('my.payments.new');
    Route::post('/my/payments', [MyPaymentsController::class, 'store'])->name('my.payments.store');

    // Admin básico (usuarios)
    Route::prefix('admin')
        ->middleware(['role:admin,staff_l1,administrativo'])
        ->group(function () {
            Route::get('/users/create', [UserAdminController::class, 'create'])->name('admin.users.create');
            Route::post('/users/create', [UserAdminController::class, 'store'])->name('admin.users.store');
        });

    // Finanzas (sin staff_l2/docente/alumno)
    Route::prefix('admin/finance')
        ->middleware(['role:admin,staff_l1,administrativo'])
        ->group(function () {
            Route::get('/payments', [FinancePaymentsController::class, 'index'])->name('finance.payments.index');
            Route::get('/payments/{payment}', [FinancePaymentsController::class, 'show'])->name('finance.payments.show');
            Route::post('/payments/{payment}/approve', [FinancePaymentsController::class, 'approve'])->name('finance.payments.approve');
            Route::post('/payments/{payment}/reject', [FinancePaymentsController::class, 'reject'])->name('finance.payments.reject');
            Route::post('/payments/{payment}/refund', [FinancePaymentsController::class, 'refund'])->name('finance.payments.refund');
        });

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

// Reset contraseña (DNI -> OTP -> nueva contraseña)
Route::get('/reset-password', [PasswordResetOtpController::class, 'show'])->name('reset.show');
Route::post('/reset-password', [PasswordResetOtpController::class, 'sendOtp'])->name('reset.send');
Route::get('/reset-password/verify', [PasswordResetOtpController::class, 'showVerify'])->name('reset.verify');
Route::post('/reset-password/verify', [PasswordResetOtpController::class, 'verify'])->name('reset.verify.post');
Route::get('/reset-password/new', [PasswordResetOtpController::class, 'showPassword'])->name('reset.password');
Route::post('/reset-password/new', [PasswordResetOtpController::class, 'setPassword'])->name('reset.password.post');

// Ruta de prueba admin
Route::get('/admin/test', function () {
    return 'OK admin';
})->middleware(['auth', 'role:admin']);
