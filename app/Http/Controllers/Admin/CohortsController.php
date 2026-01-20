<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Cohort;
use Illuminate\Http\Request;

class CohortsController extends Controller
{
    public function index(Course $course)
    {
        $cohorts = Cohort::where('course_id',$course->id)->orderByDesc('id')->paginate(20);
        return view('admin.cohorts.index', compact('course','cohorts'));
    }

    public function create(Course $course)
    {
        return view('admin.cohorts.create', compact('course'));
    }

    public function store(Request $r, Course $course)
    {
        $r->validate([
            'name' => ['required','string','max:120'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date'],
            'price_total' => ['required','numeric','min:0'],
            'installments_count' => ['required','integer','min:1','max:10'],
            'is_active' => ['nullable'],
        ]);

        Cohort::create([
            'course_id' => $course->id,
            'name' => $r->name,
            'start_date' => $r->start_date,
            'end_date' => $r->end_date,
            'price_total' => $r->price_total,
            'installments_count' => $r->installments_count,
            'is_active' => (bool)$r->is_active,
        ]);

        return redirect()->route('admin.cohorts.index', $course)->with('ok','Cohorte creada.');
    }

    public function edit(Course $course, Cohort $cohort)
    {
        abort_unless($cohort->course_id === $course->id, 404);
        return view('admin.cohorts.edit', compact('course','cohort'));
    }

    public function update(Request $r, Course $course, Cohort $cohort)
    {
        abort_unless($cohort->course_id === $course->id, 404);

        $r->validate([
            'name' => ['required','string','max:120'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date'],
            'price_total' => ['required','numeric','min:0'],
            'installments_count' => ['required','integer','min:1','max:10'],
            'is_active' => ['nullable'],
        ]);

        $cohort->update([
            'name' => $r->name,
            'start_date' => $r->start_date,
            'end_date' => $r->end_date,
            'price_total' => $r->price_total,
            'installments_count' => $r->installments_count,
            'is_active' => (bool)$r->is_active,
        ]);

        return redirect()->route('admin.cohorts.index', $course)->with('ok','Cohorte actualizada.');
    }
}
