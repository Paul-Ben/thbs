<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LevelController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();
        $levels = Level::with('semester')->paginate(15);
        return view('superadmin.levels.index', compact('levels', 'authUser'));
    }

    public function create()
    {
        $semesters = Semester::all();
        return view('superadmin.levels.create', compact('semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        Level::create($request->all());

        return Redirect::route('superadmin.levels.index')->with('success', 'Level created successfully.');
    }

    public function show(Level $level)
    {
        $level->load('semester');
        return view('superadmin.levels.show', compact('level'));
    }

    public function edit(Level $level)
    {
        $semesters = Semester::all();
        return view('superadmin.levels.edit', compact('level', 'semesters'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        $level->update($request->all());

        return Redirect::route('superadmin.levels.index')->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return Redirect::route('superadmin.levels.index')->with('success', 'Level deleted successfully.');
    }
}