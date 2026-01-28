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
use App\Http\Controllers\Admin\Academic\EnrollmentsController as AcademicEnrollmentsController;

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PreinscriptionController;




use App\Http\Controllers\PublicEnrollmentController;

Route::prefix('admin/academic')
  ->middleware(['auth','role:admin,staff_l1,staff_l2,administrativo,docente'])
  ->group(function () {

    Route::get('/', fn() => redirect()->route('admin.academic.courses.index'))
      ->name('admin.academic.home');

    // =========================
    // Cursos
    // =========================
    Route::get('/courses', [AcademicCoursesController::class,'index'])
      ->name('admin.academic.courses.index');

    Route::get('/courses/create', [AcademicCoursesController::class,'create'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.courses.create');

    Route::post('/courses', [AcademicCoursesController::class,'store'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.courses.store');

    Route::get('/courses/{course}/edit', [AcademicCoursesController::class,'edit'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.courses.edit');

    Route::put('/courses/{course}', [AcademicCoursesController::class,'update'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.courses.update');


    // =========================
    // Cohortes (global + por curso)
    // =========================
    // Listado global de cohortes (para tu menú "Cohortes")
    Route::get('/cohorts', [AcademicCohortsController::class, 'all'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.all');

    // Listado por curso
    Route::get('/courses/{course}/cohorts', [AcademicCohortsController::class,'index'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.index');

    Route::get('/courses/{course}/cohorts/create', [AcademicCohortsController::class,'create'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.create');

    Route::post('/courses/{course}/cohorts', [AcademicCohortsController::class,'store'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.store');

    Route::get('/courses/{course}/cohorts/{cohort}/edit', [AcademicCohortsController::class,'edit'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.edit');

    Route::put('/courses/{course}/cohorts/{cohort}', [AcademicCohortsController::class,'update'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.update');

    Route::delete('/courses/{course}/cohorts/{cohort}', [AcademicCohortsController::class,'destroy'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.destroy');


    // =========================
    // Preinscripciones
    // =========================
    Route::get('/preinscripciones', [AcademicEnrollmentsController::class,'preinscriptions'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.preinscriptions.index');

    Route::post('/enrollments/{enrollment}/mark-inscripto', [AcademicEnrollmentsController::class,'markInscripto'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.mark_inscripto');


    // =========================
    // Matrículas / Inscripciones
    // =========================
    Route::get('/enrollments/create', [AcademicEnrollmentsController::class,'create'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.create');

    Route::post('/enrollments', [AcademicEnrollmentsController::class,'store'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.store');

    Route::post('/enrollments/{enrollment}/installments/generate', [AcademicEnrollmentsController::class,'generateInstallments'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.installments.generate');
  });
