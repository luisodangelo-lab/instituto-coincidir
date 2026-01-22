<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CoursesController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('title')->paginate(20);
        return view('admin.academic.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.academic.courses.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'code' => ['required','string','max:50','unique:courses,code'],
            'title' => ['required','string','max:180'],
            'type' => ['required','in:curso,tecnicatura'],
            'description' => ['nullable','string'],

            'modality' => ['nullable','string','max:80'],
            'months' => ['nullable','integer','min:1','max:60'],
            'duration_weeks' => ['nullable','integer','min:1','max:260'],
            'hours_total' => ['nullable','integer','min:1','max:5000'],

            'expediente_number' => ['nullable','string','max:100'],
            'resolution_number' => ['nullable','string','max:100'],
            'presentation_date' => ['nullable','date'],
            'ministry_approved' => ['nullable','boolean'],

            'is_active' => ['nullable','boolean'],
        ], [
            'type.required' => 'El tipo (curso/tecnicatura) es obligatorio.',
            'code.required' => 'El código es obligatorio.',
            'title.required' => 'El título es obligatorio.',
        ]);

        $data['is_active'] = (bool) $r->boolean('is_active');
        $data['ministry_approved'] = (bool) $r->boolean('ministry_approved');
        $data['code'] = strtoupper(trim($data['code']));

        Course::create($data);

        return redirect()->route('admin.academic.courses.index')->with('ok','Curso creado.');
    }

    public function edit(Course $course)
    {
        return view('admin.academic.courses.edit', compact('course'));
    }

    public function update(Request $r, Course $course)
    {
        $data = $r->validate([
            'code' => ['required','string','max:50', Rule::unique('courses','code')->ignore($course->id)],
            'title' => ['required','string','max:180'],
            'type' => ['required','in:curso,tecnicatura'],
            'description' => ['nullable','string'],

            'modality' => ['nullable','string','max:80'],
            'months' => ['nullable','integer','min:1','max:60'],
            'duration_weeks' => ['nullable','integer','min:1','max:260'],
            'hours_total' => ['nullable','integer','min:1','max:5000'],

            'expediente_number' => ['nullable','string','max:100'],
            'resolution_number' => ['nullable','string','max:100'],
            'presentation_date' => ['nullable','date'],
            'ministry_approved' => ['nullable','boolean'],

            'is_active' => ['nullable','boolean'],
        ]);

        $data['is_active'] = (bool) $r->boolean('is_active');
        $data['ministry_approved'] = (bool) $r->boolean('ministry_approved');
        $data['code'] = strtoupper(trim($data['code']));

        $course->update($data);

        return redirect()->route('admin.academic.courses.index')->with('ok','Curso actualizado.');
    }
}
