<?php

namespace App\Http\Controllers;

use App\Models\Course;

class CatalogController extends Controller
{
    public function index()
    {
        $courses = Course::query()
            ->where('is_active', true)
            ->with(['cohorts' => function ($q) {
                $q->where('enrollment_open', true)
                  ->orderBy('start_date');
            }])
            ->orderBy('title')
            ->get();

        return view('catalog.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load(['cohorts' => function ($q) {
            $q->where('enrollment_open', true)
              ->orderBy('start_date');
        }]);

        return view('catalog.show', compact('course'));
    }
}
