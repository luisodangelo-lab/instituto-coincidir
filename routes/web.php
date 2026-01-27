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


use App\Http\Controllers\Admin\Academic\CoursesController as AcademicCoursesController;
use App\Http\Controllers\Admin\Academic\CohortsController as AcademicCohortsController;
use App\Http\Controllers\Admin\Academic\EnrollmentsController as AcademicEnrollmentsController;

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PreinscriptionController;


use App\Http\Controllers\PublicEnrollmentController;

Route::get('/inscribirme/{course}', [PublicEnrollmentController::class, 'show'])
    ->name('public.enroll.show');

Route::post('/inscribirme/{course}', [PublicEnrollmentController::class, 'store'])
    ->name('public.enroll.store');

Route::get('/comprobante/{token}', [PublicEnrollmentController::class, 'showReceipt'])
    ->name('public.receipt.show');

Route::post('/comprobante/{token}', [PublicEnrollmentController::class, 'storeReceipt'])
    ->name('public.receipt.store');


Route::get('/cursos', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/cursos/{course:code}', [CatalogController::class, 'show'])->name('catalog.show');

Route::middleware('auth')->group(function () {
    Route::post('/cohortes/{cohort}/preinscribir', [PreinscriptionController::class, 'store'])
        ->name('preinscriptions.store');
});

    Route::get('/courses/{course}/edit', [AcademicCoursesController::class,'edit'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.courses.edit');

    Route::put('/courses/{course}', [AcademicCoursesController::class,'update'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.courses.update');

Route::prefix('admin/academic')
  ->middleware(['auth','role:admin,staff_l1,staff_l2,administrativo,docente'])
  ->group(function () {

    // Preinscripciones (preinscripto / pendiente_pago)
    Route::get('/preinscripciones', [AcademicEnrollmentsController::class,'preinscriptions'])
    ->middleware('role:admin,staff_l1,administrativo')
    ->name('admin.academic.preinscriptions.index');

    Route::post('/enrollments/{enrollment}/mark-inscripto', [AcademicEnrollmentsController::class,'markInscripto'])
    ->middleware('role:admin,staff_l1,administrativo')
    ->name('admin.academic.enrollments.mark_inscripto');


Route::delete('admin/academic/courses/{course}/cohorts/{cohort}', [CohortsController::class, 'destroy'])
  ->name('admin.academic.cohorts.destroy');


    Route::get('/preinscripciones', [AcademicEnrollmentsController::class,'preinscriptions'])
    ->middleware('role:admin,staff_l1,administrativo')
    ->name('admin.academic.preinscriptions.index');

    Route::post('/enrollments/{enrollment}/mark-inscripto', [AcademicEnrollmentsController::class,'markInscripto'])
    ->middleware('role:admin,staff_l1,administrativo')
    ->name('admin.academic.enrollments.mark_inscripto');

Route::get('admin/academic/courses/{course}/cohorts/{cohort}/edit', [CohortsController::class, 'edit'])
  ->name('admin.academic.cohorts.edit');

Route::put('admin/academic/courses/{course}/cohorts/{cohort}', [CohortsController::class, 'update'])
  ->name('admin.academic.cohorts.update');
Route::get('admin/academic/courses/{course}/cohorts', [CohortsController::class, 'index'])
  ->name('admin.academic.cohorts.index');

Route::get('admin/academic/courses/{course}/cohorts/{cohort}/edit', [CohortsController::class, 'edit'])
  ->name('admin.academic.cohorts.edit');

Route::put('admin/academic/courses/{course}/cohorts/{cohort}', [CohortsController::class, 'update'])
  ->name('admin.academic.cohorts.update');

    Route::get('/', fn() => redirect()->route('admin.academic.courses.index'))
      ->name('admin.academic.home');

    // Cursos
    Route::get('/courses', [AcademicCoursesController::class,'index'])
      ->name('admin.academic.courses.index');

    Route::get('/courses/create', [AcademicCoursesController::class,'create'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.courses.create');

    Route::post('/courses', [AcademicCoursesController::class,'store'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.courses.store');

    // Cohortes (por curso)
    Route::get('/courses/{course}/cohorts/create', [AcademicCohortsController::class,'create'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.create');

    Route::post('/courses/{course}/cohorts', [AcademicCohortsController::class,'store'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.store');

    // Matrículas/inscripciones
    Route::get('/enrollments/create', [AcademicEnrollmentsController::class,'create'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.create');

    Route::post('/enrollments', [AcademicEnrollmentsController::class,'store'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.store');

    // Generar cuotas
    Route::post('/enrollments/{enrollment}/installments/generate', [AcademicEnrollmentsController::class,'generateInstallments'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.installments.generate');
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
