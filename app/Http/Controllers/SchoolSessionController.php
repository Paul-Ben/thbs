<?php

namespace App\Http\Controllers;

use App\Models\SchoolSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SchoolSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();
        $schoolSessions = SchoolSession::orderBy('year', 'desc')->paginate(10);
        return view('superadmin.school-sessions.index', compact('schoolSessions', 'authUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUser = Auth::user();
        return view('superadmin.school-sessions.create', compact('authUser'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string|max:255|unique:school_sessions,session_name',
            'year' => 'required|integer|min:2000|max:2100',
            'is_current' => 'boolean'
        ]);

        // If this session is being set as current, unset all other current sessions
        if ($request->has('is_current') && $request->is_current) {
            SchoolSession::where('is_current', true)->update(['is_current' => false]);
        }

        SchoolSession::create([
            'session_name' => $request->session_name,
            'year' => $request->year,
            'is_current' => $request->has('is_current') ? $request->is_current : false
        ]);

        return redirect()->route('superadmin.school-sessions.index')
            ->with('success', 'School session created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolSession $schoolSession)
    {
        $authUser = Auth::user();
        $schoolSession->load('semesters');
        return view('superadmin.school-sessions.show', compact('schoolSession', 'authUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolSession $schoolSession)
    {
        $authUser = Auth::user();
        return view('superadmin.school-sessions.edit', compact('schoolSession', 'authUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolSession $schoolSession)
    {
        $request->validate([
            'session_name' => 'required|string|max:255|unique:school_sessions,session_name,' . $schoolSession->id,
            'year' => 'required|integer|min:2000|max:2100',
            'is_current' => 'boolean'
        ]);

        // If this session is being set as current, unset all other current sessions
        if ($request->has('is_current') && $request->is_current) {
            SchoolSession::where('is_current', true)
                ->where('id', '!=', $schoolSession->id)
                ->update(['is_current' => false]);
        }

        $schoolSession->update([
            'session_name' => $request->session_name,
            'year' => $request->year,
            'is_current' => $request->has('is_current') ? $request->is_current : false
        ]);

        return redirect()->route('superadmin.school-sessions.index')
            ->with('success', 'School session updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolSession $schoolSession)
    {
        // Check if this session has related semesters
        if ($schoolSession->semesters()->count() > 0) {
            return redirect()->route('superadmin.school-sessions.index')
                ->with('error', 'Cannot delete school session. It has related semesters.');
        }

        $schoolSession->delete();

        return redirect()->route('superadmin.school-sessions.index')
            ->with('success', 'School session deleted successfully.');
    }

    /**
     * Set a school session as current
     */
    public function setCurrent(SchoolSession $schoolSession)
    {
        DB::transaction(function () use ($schoolSession) {
            // Unset all current sessions
            SchoolSession::where('is_current', true)->update(['is_current' => false]);
            
            // Set this session as current
            $schoolSession->update(['is_current' => true]);
        });

        return redirect()->route('superadmin.school-sessions.index')
            ->with('success', 'School session set as current successfully.');
    }
}