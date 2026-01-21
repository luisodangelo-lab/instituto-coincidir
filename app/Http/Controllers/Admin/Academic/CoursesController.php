<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

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
            'is_active' => ['nullable','boolean'],
        ]);

        $data['is_active'] = (int)($data['is_active'] ?? 1);

        Course::create($data);

        return redirect()
            ->route('admin.academic.courses.index')
            ->with('ok', 'Curso creado.');
    }
}
