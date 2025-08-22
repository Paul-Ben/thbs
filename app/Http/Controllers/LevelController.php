<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class LevelController extends Controller
{
    public function index(): View 
    {
        $authUser = Auth::user();
        $levels = Level::with('semester')->paginate(15);
        return view('superadmin.levels.index', compact('levels', 'authUser'));
    }

    public function create(): View
    {
        $semesters = Semester::all();
        return view('superadmin.levels.create', compact('semesters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        Level::create($request->all());

        ToastMagic::success('Level created successfully.');

        return redirect()->route('superadmin.levels.index');
    }

    public function show(Level $level): View
    {
        $level->load('semester');
        return view('superadmin.levels.show', compact('level'));
    }

    public function edit(Level $level): View
    {
        $semesters = Semester::all();
        return view('superadmin.levels.edit', compact('level', 'semesters'));
    }

    public function update(Request $request, Level $level): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        $level->update($request->all());

        ToastMagic::success('Level updated successfully.');

        return redirect()->route('superadmin.levels.index');
    }

    public function destroy(Level $level): RedirectResponse
    {
        $level->delete();

        ToastMagic::success('Level deleted successfully.');

        return redirect()->route('superadmin.levels.index');
    }
}