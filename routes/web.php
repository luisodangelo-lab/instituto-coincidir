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

use App\Http\Controllers\Admin\Academic\CohortsController;
use App\Http\Controllers\Admin\Academic\CoursesController as AcademicCoursesController;
use App\Http\Controllers\Admin\Academic\EnrollmentsController as AcademicEnrollmentsController;

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PreinscriptionController;

use App\Http\Controllers\Admin\Academic\EnrollmentsController;

use App\Http\Controllers\PublicEnrollmentController;

    // =========================
    // Cohortes (global + por curso)
    // =========================

    // Listado global (para menú "Cohortes")
    Route::get('/cohorts', [CohortsController::class, 'all'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.all');

    // Listado por curso
    Route::get('/courses/{course}/cohorts', [CohortsController::class, 'index'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.index');

    Route::get('/courses/{course}/cohorts/create', [CohortsController::class, 'create'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.create');

    Route::post('/courses/{course}/cohorts', [CohortsController::class, 'store'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.store');

    Route::get('/courses/{course}/cohorts/{cohort}/edit', [CohortsController::class, 'edit'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.edit');

    Route::put('/courses/{course}/cohorts/{cohort}', [CohortsController::class, 'update'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.update');

    Route::delete('/courses/{course}/cohorts/{cohort}', [CohortsController::class, 'destroy'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.cohorts.destroy');


    // =========================
    // Preinscripciones
    // =========================
    Route::get('/preinscripciones', [EnrollmentsController::class, 'preinscriptions'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.preinscriptions.index');

    Route::post('/enrollments/{enrollment}/mark-inscripto', [EnrollmentsController::class, 'markInscripto'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.mark_inscripto');


    // =========================
    // Matrículas / Inscripciones
    // =========================
    Route::get('/enrollments/create', [EnrollmentsController::class, 'create'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.create');

    Route::post('/enrollments', [EnrollmentsController::class, 'store'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.store');

    Route::post('/enrollments/{enrollment}/installments/generate', [EnrollmentsController::class, 'generateInstallments'])
      ->middleware('role:admin,staff_l1,administrativo')
      ->name('admin.academic.enrollments.installments.generate');
