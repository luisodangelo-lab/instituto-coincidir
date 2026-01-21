<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Cohort;
use Illuminate\Http\Request;

class CohortsController extends Controller
{
    public function create(Course $course)
    {
        return view('admin.academic.cohorts_create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date'],
            'price_total' => ['required','numeric','min:0'],
            'installments_count' => ['required','integer','min:1','max:12'],
            'installment_due_day' => ['required','integer','min:1','max:28'],
            'enrollment_open' => ['nullable','boolean'],
            'max_seats' => ['nullable','integer','min:1'],
        ]);

        $data['course_id'] = $course->id;
        $data['enrollment_open'] = (int)($data['enrollment_open'] ?? 1);

        Cohort::create($data);

        return redirect()
            ->route('admin.academic.courses.index')
            ->with('ok', 'Cohorte creada para el curso '.$course->code.'.');
    }
}
