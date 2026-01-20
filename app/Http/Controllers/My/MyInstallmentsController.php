<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyInstallmentsController extends Controller
{
    public function index(Request $request)
    {
        $u = $request->user();

        $enrollments = $u->enrollments()
            ->with(['cohort.course','installments' => fn($q) => $q->orderBy('number')])
            ->latest()
            ->get();

        return view('my.installments_index', compact('u','enrollments'));
    }
}
