<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\SchoolSession;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $authUser = Auth::user();
        $semesters = Semester::with('session')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('superadmin.semesters.index', compact('semesters', 'authUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $authUser = Auth::user();
        $schoolSessions = SchoolSession::orderBy('session_name', 'desc')->get();
        return view('superadmin.semesters.create', compact('schoolSessions', 'authUser'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'semester_name' => 'required|string|max:255',
            'school_session_id' => 'required|exists:school_sessions,id',
            'is_current' => 'boolean'
        ]);

        // If this semester is set as current, unset all other current semesters
        if ($request->has('is_current') && $request->is_current) {
            Semester::where('is_current', true)->update(['is_current' => false]);
            $validated['is_current'] = true;
        } else {
            $validated['is_current'] = false;
        }

        Semester::create($validated);

        return redirect()->route('superadmin.semesters.index')
            ->with('success', 'Semester created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester): View
    {
        $authUser = Auth::user();
        $semester->load(['session', 'courses', 'courseRegistrations', 'results']);
        
        // Get statistics
        $stats = [
            'total_courses' => $semester->courses()->count(),
            'total_registrations' => $semester->courseRegistrations()->count(),
            'total_results' => $semester->results()->count(),
            'days_since_created' => $semester->created_at->diffInDays(now()),
            'days_since_updated' => $semester->updated_at->diffInDays(now())
        ];

        return view('superadmin.semesters.show', compact('semester', 'stats', 'authUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester): View
    {
        $authUser = Auth::user();
        $schoolSessions = SchoolSession::orderBy('session_name', 'desc')->get();
        $semester->load(['session', 'courses', 'courseRegistrations']);
        
        return view('superadmin.semesters.edit', compact('semester', 'schoolSessions', 'authUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Semester $semester): RedirectResponse
    {
        $validated = $request->validate([
            'semester_name' => 'required|string|max:255',
            'school_session_id' => 'required|exists:school_sessions,id',
            'is_current' => 'boolean'
        ]);

        // If this semester is set as current, unset all other current semesters
        if ($request->has('is_current') && $request->is_current) {
            Semester::where('id', '!=', $semester->id)
                ->where('is_current', true)
                ->update(['is_current' => false]);
            $validated['is_current'] = true;
        } else {
            $validated['is_current'] = false;
        }

        $semester->update($validated);

        return redirect()->route('superadmin.semesters.index')
            ->with('success', 'Semester updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semester $semester): RedirectResponse
    {
        // Check if semester has related data
        $hasRelatedData = $semester->courses()->exists() || 
                         $semester->courseRegistrations()->exists() || 
                         $semester->results()->exists();

        if ($hasRelatedData) {
            return redirect()->route('superadmin.semesters.index')
                ->with('error', 'Cannot delete semester. It has related courses, registrations, or results.');
        }

        $semester->delete();

        return redirect()->route('superadmin.semesters.index')
            ->with('success', 'Semester deleted successfully.');
    }

    /**
     * Set the specified semester as current.
     */
    public function setCurrent(Semester $semester): RedirectResponse
    {
        // Unset all current semesters
        Semester::where('is_current', true)->update(['is_current' => false]);
        
        // Set this semester as current
        $semester->update(['is_current' => true]);

        return redirect()->route('superadmin.semesters.index')
            ->with('success', 'Semester set as current successfully.');
    }
}