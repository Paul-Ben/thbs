<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Programme;
use App\Models\Level;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CourseController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();
        $courses = Course::with(['programme', 'level', 'semester'])->paginate(15);
        return view('superadmin.courses.index', compact('courses', 'authUser'));       
    }

    public function create()
    {
        $programmes = Programme::all();
        $levels = Level::all();
        $semesters = Semester::all();
        return view('superadmin.courses.create', compact('programmes', 'levels', 'semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:courses,code',
            'title' => 'required|string|max:255',
            'credit_units' => 'required|integer|min:1|max:6',
            'programme_id' => 'required|exists:programmes,id',
            'level_id' => 'required|exists:levels,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        Course::create($request->all());

        return Redirect::route('superadmin.courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load(['programme', 'level', 'semester']);
        return view('superadmin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $programmes = Programme::all();
        $levels = Level::all();
        $semesters = Semester::all();
        return view('superadmin.courses.edit', compact('course', 'programmes', 'levels', 'semesters'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:courses,code,' . $course->id,
            'title' => 'required|string|max:255',
            'credit_units' => 'required|integer|min:1|max:6',
            'programme_id' => 'required|exists:programmes,id',
            'level_id' => 'required|exists:levels,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        $course->update($request->all());

        return Redirect::route('superadmin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return Redirect::route('superadmin.courses.index')->with('success', 'Course deleted successfully.');
    }
}