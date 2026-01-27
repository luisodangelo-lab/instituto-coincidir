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
    ->route('admin.academic.cohorts.index', $course)
    ->with('ok', 'Cohorte creada');

    }



public function index(Course $course)
{
    $cohorts = Cohort::where('course_id', $course->id)
        ->orderByDesc('id')
        ->get();

    return view('admin.academic.cohorts_index', compact('course', 'cohorts'));
}

public function edit(Course $course, Cohort $cohort)
{
    abort_unless($cohort->course_id === $course->id, 404);

    return view('admin.academic.cohorts_edit', compact('course', 'cohort'));
}

public function update(Request $request, Course $course, Cohort $cohort)
{
    abort_unless($cohort->course_id === $course->id, 404);

    $data = $request->validate([
        'name' => ['required','string','max:120'],
        'start_date' => ['nullable','date'],
        'end_date' => ['nullable','date'],
        'price_total' => ['required','numeric','min:0'],
        'installments_count' => ['required','integer','min:1','max:12'],
        'installment_due_day' => ['required','integer','min:1','max:28'],
        'enrollment_open' => ['nullable','boolean'],
        'max_seats' => ['nullable','integer','min:1'],

        // extras que existen en tu tabla y está bueno poder editar
        'label' => ['nullable','string','max:255'],
        'capacity' => ['nullable','integer','min:0'],
        'is_public' => ['nullable','boolean'],
        'notes' => ['nullable','string'],
    ]);

    $data['enrollment_open'] = (int)($request->boolean('enrollment_open'));
    $data['is_public'] = (int)($request->boolean('is_public'));

    $cohort->update($data);

    return redirect()
        ->route('admin.academic.cohorts.edit', [$course, $cohort])
        ->with('ok', 'Cohorte actualizada.');
}

}
