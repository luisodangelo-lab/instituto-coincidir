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
        $courses = Course::orderByDesc('id')->paginate(20);
        return view('admin.academic.courses_index', compact('courses'));
    }

    public function create()
    {
        return view('admin.academic.courses_create');
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'code' => ['required','string','max:50','unique:courses,code'],
        'title' => ['required','string','max:255'],
        'description' => ['nullable','string'],

        'type' => ['required','in:curso,tecnicatura'],
        'modality' => ['nullable','string','max:80'],
        'months' => ['nullable','integer','min:1','max:60'],
        'duration_weeks' => ['nullable','integer','min:1','max:260'],
        'hours_total' => ['nullable','integer','min:1','max:5000'],

        'is_active' => ['nullable','boolean'],

        'expediente_number' => ['nullable','string','max:100'],
        'resolution_number' => ['nullable','string','max:100'],
        'presentation_date' => ['nullable','date'],
        'ministry_approved' => ['nullable','boolean'],
    ]);

    $data['is_active'] = (bool)$request->boolean('is_active');
    $data['ministry_approved'] = (bool)$request->boolean('ministry_approved');
    $data['code'] = strtoupper(trim($data['code']));

    Course::create($data);

    return redirect()->route('admin.academic.courses.index')->with('ok','Curso creado.');
}
public function update(Request $request, Course $course)
{
    $data = $request->validate([
        'code' => ['required','string','max:50', Rule::unique('courses','code')->ignore($course->id)],
        'title' => ['required','string','max:255'],
        'description' => ['nullable','string'],

        'type' => ['required','in:curso,tecnicatura'],
        'modality' => ['nullable','string','max:80'],
        'months' => ['nullable','integer','min:1','max:60'],
        'duration_weeks' => ['nullable','integer','min:1','max:260'],
        'hours_total' => ['nullable','integer','min:1','max:5000'],

        'is_active' => ['nullable','boolean'],

        'expediente_number' => ['nullable','string','max:100'],
        'resolution_number' => ['nullable','string','max:100'],
        'presentation_date' => ['nullable','date'],
        'ministry_approved' => ['nullable','boolean'],
    ]);

    $data['is_active'] = (bool)$request->boolean('is_active');
    $data['ministry_approved'] = (bool)$request->boolean('ministry_approved');
    $data['code'] = strtoupper(trim($data['code']));

    $course->update($data);

    return redirect()->route('admin.academic.courses.index')->with('ok','Curso actualizado.');
}

}
