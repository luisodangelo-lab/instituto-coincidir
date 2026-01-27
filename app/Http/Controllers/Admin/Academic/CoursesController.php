<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CoursesController extends Controller
{
    public function index()
    {
        $courses = Course::orderByDesc('id')->paginate(10);
        return view('admin.academic.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.academic.courses.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'code' => ['required','string','max:50', Rule::unique('courses','code')],
            'title' => ['required','string','max:180'],
            'type' => ['required','in:curso,tecnicatura'],
            'modality' => ['nullable','string','max:80'],

            'description' => ['nullable','string'],
            'axes' => ['nullable','string'],
            'contents' => ['nullable','string'],

            'expediente_number' => ['nullable','string','max:100'],
            'resolution_number' => ['nullable','string','max:100'],
            'disposition_number' => ['nullable','string','max:100'],
            'presentation_date' => ['nullable','date'],

            // dropdown Sí/No
            'is_active' => ['required','in:0,1'],
            'ministry_approved' => ['required','in:0,1'],

            // archivos
            'cover' => ['nullable','file','mimes:jpg,jpeg,png,webp','max:8192'],
            'normative_pdf' => ['nullable','file','mimes:pdf','max:12288'],
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = (int)$data['is_active'];
        $data['ministry_approved'] = (int)$data['ministry_approved'];

        // Creamos con campos base (y después asignamos extras/archivos según existan columnas)
        $course = new Course();
        $course->code = $data['code'];
        $course->title = $data['title'];
        $course->type = $data['type'];
        $course->modality = $data['modality'] ?? null;
        $course->description = $data['description'] ?? null;

        if (Schema::hasColumn('courses','is_active')) $course->is_active = $data['is_active'];
        if (Schema::hasColumn('courses','ministry_approved')) $course->ministry_approved = $data['ministry_approved'];

        if (Schema::hasColumn('courses','axes')) $course->axes = $data['axes'] ?? null;
        if (Schema::hasColumn('courses','contents')) $course->contents = $data['contents'] ?? null;

        if (Schema::hasColumn('courses','expediente_number')) $course->expediente_number = $data['expediente_number'] ?? null;
        if (Schema::hasColumn('courses','resolution_number')) $course->resolution_number = $data['resolution_number'] ?? null;
        if (Schema::hasColumn('courses','disposition_number')) $course->disposition_number = $data['disposition_number'] ?? null;
        if (Schema::hasColumn('courses','presentation_date')) $course->presentation_date = $data['presentation_date'] ?? null;

        $course->save();

        // Subir carátula
        if ($r->hasFile('cover') && Schema::hasColumn('courses','cover_path')) {
            $course->cover_path = $r->file('cover')->store('courses/covers', 'public');
        }

        // Subir normativa PDF
        if ($r->hasFile('normative_pdf') && Schema::hasColumn('courses','normative_pdf_path')) {
            $course->normative_pdf_path = $r->file('normative_pdf')->store('courses/normativa', 'public');
        }

        $course->save();

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
            'modality' => ['nullable','string','max:80'],

            'description' => ['nullable','string'],
            'axes' => ['nullable','string'],
            'contents' => ['nullable','string'],

            'expediente_number' => ['nullable','string','max:100'],
            'resolution_number' => ['nullable','string','max:100'],
            'disposition_number' => ['nullable','string','max:100'],
            'presentation_date' => ['nullable','date'],

            'is_active' => ['required','in:0,1'],
            'ministry_approved' => ['required','in:0,1'],

            'cover' => ['nullable','file','mimes:jpg,jpeg,png,webp','max:8192'],
            'normative_pdf' => ['nullable','file','mimes:pdf','max:12288'],
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = (int)$data['is_active'];
        $data['ministry_approved'] = (int)$data['ministry_approved'];

        $course->code = $data['code'];
        $course->title = $data['title'];
        $course->type = $data['type'];
        $course->modality = $data['modality'] ?? null;
        $course->description = $data['description'] ?? null;

        if (Schema::hasColumn('courses','is_active')) $course->is_active = $data['is_active'];
        if (Schema::hasColumn('courses','ministry_approved')) $course->ministry_approved = $data['ministry_approved'];

        if (Schema::hasColumn('courses','axes')) $course->axes = $data['axes'] ?? null;
        if (Schema::hasColumn('courses','contents')) $course->contents = $data['contents'] ?? null;

        if (Schema::hasColumn('courses','expediente_number')) $course->expediente_number = $data['expediente_number'] ?? null;
        if (Schema::hasColumn('courses','resolution_number')) $course->resolution_number = $data['resolution_number'] ?? null;
        if (Schema::hasColumn('courses','disposition_number')) $course->disposition_number = $data['disposition_number'] ?? null;
        if (Schema::hasColumn('courses','presentation_date')) $course->presentation_date = $data['presentation_date'] ?? null;

        // Reemplazar carátula
        if ($r->hasFile('cover') && Schema::hasColumn('courses','cover_path')) {
            if (!empty($course->cover_path)) Storage::disk('public')->delete($course->cover_path);
            $course->cover_path = $r->file('cover')->store('courses/covers', 'public');
        }

        // Reemplazar PDF
        if ($r->hasFile('normative_pdf') && Schema::hasColumn('courses','normative_pdf_path')) {
            if (!empty($course->normative_pdf_path)) Storage::disk('public')->delete($course->normative_pdf_path);
            $course->normative_pdf_path = $r->file('normative_pdf')->store('courses/normativa', 'public');
        }

        $course->save();

        return redirect()->route('admin.academic.courses.index')->with('ok','Curso actualizado.');
    }
}
