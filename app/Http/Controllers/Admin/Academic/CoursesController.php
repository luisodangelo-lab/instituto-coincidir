<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;





class CoursesController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('title')->paginate(20);
        return view('admin.academic.courses.index', compact('courses'));
    }

    public function create()
{
    return view('admin.academic.courses.create', [
        'course' => new Course(),
    ]);
}


    public function store(Request $request)
{
    $data = $request->validate([
        'title' => ['required','string','max:255'],
        'code'  => ['required','string','max:80', Rule::unique('courses','code')],
        'type'  => ['nullable','string','max:80'],
        'category' => ['nullable','string','max:80'],

        'description' => ['nullable','string'],
        'resolution'  => ['nullable','string','max:255'],
        'expediente'  => ['nullable','string','max:255'],

        'axes'     => ['nullable','string'],
        'contents' => ['nullable','string'],

        'cover'    => ['nullable','image','max:4096'],
        'brochure' => ['nullable','file','mimes:pdf','max:10240'],
    ]);

    $data['is_active'] = (bool) $request->input('is_active', false);

    if ($request->hasFile('cover')) {
        $data['cover_path'] = $request->file('cover')->store('courses/covers', 'public');
    }
    if ($request->hasFile('brochure')) {
        $data['brochure_path'] = $request->file('brochure')->store('courses/brochures', 'public');
    }

    Course::create($data);

    return redirect()->route('admin.academic.courses.index')->with('ok', 'Curso creado.');
}


    public function edit(Course $course)
    {
        return view('admin.academic.courses.edit', compact('course'));
    }

public function update(Request $request, Course $course)
{
    $data = $request->validate([
        'title' => ['required','string','max:255'],
        'code'  => ['required','string','max:80', Rule::unique('courses','code')->ignore($course->id)],
        'type'  => ['nullable','string','max:80'],
        'category' => ['nullable','string','max:80'],

        'description' => ['nullable','string'],
        'resolution'  => ['nullable','string','max:255'],
        'expediente'  => ['nullable','string','max:255'],

        'axes'     => ['nullable','string'],
        'contents' => ['nullable','string'],

        'cover'    => ['nullable','image','max:4096'],
        'brochure' => ['nullable','file','mimes:pdf','max:10240'],
    ]);

    $data['is_active'] = (bool) $request->input('is_active', false);

    if ($request->hasFile('cover')) {
        if ($course->cover_path) Storage::disk('public')->delete($course->cover_path);
        $data['cover_path'] = $request->file('cover')->store('courses/covers', 'public');
    }

    if ($request->hasFile('brochure')) {
        if ($course->brochure_path) Storage::disk('public')->delete($course->brochure_path);
        $data['brochure_path'] = $request->file('brochure')->store('courses/brochures', 'public');
    }

    $course->update($data);

    return redirect()->route('admin.academic.courses.index')->with('ok', 'Curso actualizado.');
}


}
