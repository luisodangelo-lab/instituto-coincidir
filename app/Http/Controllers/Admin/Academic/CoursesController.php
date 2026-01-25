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
        'disposition_number' => ['nullable','string','max:100'],

        'presentation_date' => ['nullable','date'],
        'ministry_approved' => ['nullable','boolean'],
        'is_active' => ['nullable','boolean'],

        // Archivos
        'cover' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        'brochure' => ['nullable','file','mimes:pdf','max:10240'],
        
        // Textos largos
        'axes' => ['nullable','string'],
        'contents' => ['nullable','string'],
        'cover_image' => ['nullable','image','max:3072'],
        'brochure_pdf' => ['nullable','file','mimes:pdf','max:10240'],

    ], [
        'type.required' => 'El tipo (curso/tecnicatura) es obligatorio.',
        'code.required' => 'El código es obligatorio.',
        'title.required' => 'El título es obligatorio.',
    ]);

$data['is_active'] = $r->boolean('is_active');
$data['ministry_approved'] = $r->boolean('ministry_approved');

if ($r->hasFile('cover_image')) {
    $data['cover_image_path'] = $r->file('cover_image')->store('courses/covers', 'public');
}

if ($r->hasFile('brochure_pdf')) {
    $data['brochure_pdf_path'] = $r->file('brochure_pdf')->store('courses/brochures', 'public');
}


    // Guardamos archivos en variables (y los sacamos de $data para que NO se metan a la DB)
    $coverFile = $r->file('cover');
    $brochureFile = $r->file('brochure');
    unset($data['cover'], $data['brochure']);

    // Extra fields (los guardamos aparte para no depender de fillable)
    $disposition = $data['disposition_number'] ?? null;
    $axes = $data['axes'] ?? null;
    $contents = $data['contents'] ?? null;
    unset($data['disposition_number'], $data['axes'], $data['contents']);

    $data['is_active'] = (bool) $r->boolean('is_active');
    $data['ministry_approved'] = (bool) $r->boolean('ministry_approved');
    $data['code'] = strtoupper(trim($data['code']));

    // Crea el curso con campos “base”
    $course = Course::create($data);

    // Guarda campos nuevos si existen columnas
    if (Schema::hasColumn('courses', 'disposition_number')) $course->disposition_number = $disposition;
    if (Schema::hasColumn('courses', 'axes')) $course->axes = $axes;
    if (Schema::hasColumn('courses', 'contents')) $course->contents = $contents;

    // Subir imagen
    if ($coverFile && Schema::hasColumn('courses', 'cover_path')) {
        $course->cover_path = $coverFile->store('courses/covers', 'public');
    }

    // Subir PDF
    if ($brochureFile && Schema::hasColumn('courses', 'brochure_path')) {
        $course->brochure_path = $brochureFile->store('courses/brochures', 'public');
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
        'description' => ['nullable','string'],

        'modality' => ['nullable','string','max:80'],
        'months' => ['nullable','integer','min:1','max:60'],
        'duration_weeks' => ['nullable','integer','min:1','max:260'],
        'hours_total' => ['nullable','integer','min:1','max:5000'],

        'expediente_number' => ['nullable','string','max:100'],
        'resolution_number' => ['nullable','string','max:100'],
        'disposition_number' => ['nullable','string','max:100'],

        'presentation_date' => ['nullable','date'],
        'ministry_approved' => ['nullable','boolean'],
        'is_active' => ['nullable','boolean'],

        // Archivos
        'axes' => ['nullable','string'],
        'contents' => ['nullable','string'],
        'cover_image' => ['nullable','image','max:3072'],
        'brochure_pdf' => ['nullable','file','mimes:pdf','max:10240'],


        // Textos largos
        'axes' => ['nullable','string'],
        'contents' => ['nullable','string'],
    ]);


    $data['is_active'] = $r->boolean('is_active');
$data['ministry_approved'] = $r->boolean('ministry_approved');

if ($r->hasFile('cover_image')) {
    if ($course->cover_image_path) {
        Storage::disk('public')->delete($course->cover_image_path);
    }
    $data['cover_image_path'] = $r->file('cover_image')->store('courses/covers', 'public');
}

if ($r->hasFile('brochure_pdf')) {
    if ($course->brochure_pdf_path) {
        Storage::disk('public')->delete($course->brochure_pdf_path);
    }
    $data['brochure_pdf_path'] = $r->file('brochure_pdf')->store('courses/brochures', 'public');
}

    $coverFile = $r->file('cover');
    $brochureFile = $r->file('brochure');
    unset($data['cover'], $data['brochure']);

    $disposition = $data['disposition_number'] ?? null;
    $axes = $data['axes'] ?? null;
    $contents = $data['contents'] ?? null;
    unset($data['disposition_number'], $data['axes'], $data['contents']);

    $data['is_active'] = (bool) $r->boolean('is_active');
    $data['ministry_approved'] = (bool) $r->boolean('ministry_approved');
    $data['code'] = strtoupper(trim($data['code']));

    // Actualiza campos base
    $course->update($data);

    // Campos nuevos (sin depender fillable)
    if (Schema::hasColumn('courses', 'disposition_number')) $course->disposition_number = $disposition;
    if (Schema::hasColumn('courses', 'axes')) $course->axes = $axes;
    if (Schema::hasColumn('courses', 'contents')) $course->contents = $contents;

    // Cover: si suben una nueva, borramos la vieja
    if ($coverFile && Schema::hasColumn('courses', 'cover_path')) {
        if (!empty($course->cover_path)) {
            Storage::disk('public')->delete($course->cover_path);
        }
        $course->cover_path = $coverFile->store('courses/covers', 'public');
    }

    // Brochure: si suben uno nuevo, borramos el viejo
    if ($brochureFile && Schema::hasColumn('courses', 'brochure_path')) {
        if (!empty($course->brochure_path)) {
            Storage::disk('public')->delete($course->brochure_path);
        }
        $course->brochure_path = $brochureFile->store('courses/brochures', 'public');
    }

    $course->save();

    return redirect()->route('admin.academic.courses.index')->with('ok','Curso actualizado.');
}

}
