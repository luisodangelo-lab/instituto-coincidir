<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('title')->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'code' => ['required','string','max:50','unique:courses,code'],
            'title' => ['required','string','max:180'],
            'type' => ['required','in:curso,tecnicatura'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable'],
        ]);

        Course::create([
            'code' => $r->code,
            'title' => $r->title,
            'type' => $r->type,
            'description' => $r->description,
            'is_active' => (bool)$r->is_active,
        ]);

        return redirect()->route('admin.courses.index')->with('ok','Curso creado.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $r, Course $course)
    {
        $r->validate([
            'code' => ['required','string','max:50','unique:courses,code,'.$course->id],
            'title' => ['required','string','max:180'],
            'type' => ['required','in:curso,tecnicatura'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable'],
        ]);

        $course->update([
            'code' => $r->code,
            'title' => $r->title,
            'type' => $r->type,
            'description' => $r->description,
            'is_active' => (bool)$r->is_active,
        ]);

        return redirect()->route('admin.courses.index')->with('ok','Curso actualizado.');
    }
}
