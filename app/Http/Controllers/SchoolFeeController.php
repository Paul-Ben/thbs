<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolFee;
use App\Models\Programme;
use App\Models\SchoolSession;
use App\Models\Semester;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SchoolFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SchoolFee::with(['programme', 'schoolSession', 'semester', 'level']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('fee_type', 'like', "%{$search}%")
                  ->orWhereHas('programme', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by programme
        if ($request->filled('programme_id')) {
            $query->where('programme_id', $request->programme_id);
        }

        // Filter by session
        if ($request->filled('school_session_id')) {
            $query->where('school_session_id', $request->school_session_id);
        }

        // Filter by semester
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        // Filter by level
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        // Filter by fee type
        if ($request->filled('fee_type')) {
            $query->where('fee_type', $request->fee_type);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $schoolFees = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get filter options
        $programmes = Programme::orderBy('name')->get();
        $sessions = SchoolSession::orderBy('session_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();
        $levels = Level::orderBy('name')->get();
        $feeTypes = SchoolFee::select('fee_type')->distinct()->pluck('fee_type');

        return view('bursar.school-fees.index', compact(
            'schoolFees', 'programmes', 'sessions', 'semesters', 'levels', 'feeTypes'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programmes = Programme::orderBy('name')->get();
        $sessions = SchoolSession::orderBy('session_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();
        $levels = Level::orderBy('name')->get();

        $feeTypes = [
            'tuition' => 'Tuition Fee',
            'accommodation' => 'Accommodation Fee',
            'library' => 'Library Fee',
            'laboratory' => 'Laboratory Fee',
            'sports' => 'Sports Fee',
            'medical' => 'Medical Fee',
            'development' => 'Development Levy',
            'examination' => 'Examination Fee',
            'registration' => 'Registration Fee',
            'technology' => 'Technology Fee',
            'other' => 'Other Fee'
        ];
        
        return view('bursar.school-fees.create', compact(
            'programmes', 'sessions', 'semesters', 'levels', 'feeTypes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'school_session_id' => 'required|exists:school_sessions,id',
            'semester_id' => 'required|exists:semesters,id',
            'level_id' => 'required|exists:levels,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0|max:99999999.99',
            'currency' => 'required|string|max:3|in:NGN,USD,EUR,GBP',
            'description' => 'nullable|string|max:1000',
            'fee_type' => 'required|string|max:50',
            'due_date' => 'nullable|date|after:today',
            'is_active' => 'boolean'
        ]);

        // Check for duplicate fee configuration
        $existingFee = SchoolFee::where([
            'programme_id' => $validated['programme_id'],
            'school_session_id' => $validated['school_session_id'],
            'semester_id' => $validated['semester_id'],
            'level_id' => $validated['level_id'],
            'fee_type' => $validated['fee_type']
        ])->first();

        if ($existingFee) {
            return back()->withErrors([
                'fee_type' => 'A fee of this type already exists for the selected programme, session, semester, and level combination.'
            ])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');

        SchoolFee::create($validated);

        return redirect()->route('bursar.school-fees.index')
            ->with('success', 'School fee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolFee $schoolFee)
    {
        $schoolFee->load(['programme', 'schoolSession', 'semester', 'level']);
        return view('bursar.school-fees.show', compact('schoolFee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolFee $schoolFee)
    {
        $programmes = Programme::orderBy('name')->get();
        $sessions = SchoolSession::orderBy('session_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();
        $levels = Level::orderBy('name')->get();

        $feeTypes = [
            'tuition' => 'Tuition Fee',
            'accommodation' => 'Accommodation Fee',
            'library' => 'Library Fee',
            'laboratory' => 'Laboratory Fee',
            'sports' => 'Sports Fee',
            'medical' => 'Medical Fee',
            'development' => 'Development Levy',
            'examination' => 'Examination Fee',
            'registration' => 'Registration Fee',
            'technology' => 'Technology Fee',
            'other' => 'Other Fee'
        ];

        return view('bursar.school-fees.edit', compact(
            'schoolFee', 'programmes', 'sessions', 'semesters', 'levels', 'feeTypes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolFee $schoolFee)
    {
        // Validate the request data
        $validated = $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'school_session_id' => 'required|exists:school_sessions,id',
            'semester_id' => 'required|exists:semesters,id',
            'level_id' => 'required|exists:levels,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0|max:99999999.99',
            'currency' => 'required|string|max:3|in:NGN,USD,EUR,GBP',
            'description' => 'nullable|string|max:1000',
            'fee_type' => 'required|string|max:50',
            'due_date' => 'nullable|date|after:today',
            'is_active' => 'boolean'
        ]);

        // Check for duplicate fee configuration (excluding current record)
        $existingFee = SchoolFee::where([
            'programme_id' => $validated['programme_id'],
            'school_session_id' => $validated['school_session_id'],
            'semester_id' => $validated['semester_id'],
            'level_id' => $validated['level_id'],
            'fee_type' => $validated['fee_type']
        ])->where('id', '!=', $schoolFee->id)->first();

        if ($existingFee) {
            return back()->withErrors([
                'fee_type' => 'A fee of this type already exists for the selected programme, session, semester, and level combination.'
            ])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');

        $schoolFee->update($validated);

        return redirect()->route('bursar.school-fees.index')
            ->with('success', 'School fee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolFee $schoolFee)
    {
        try {
            // Check if there are any payments associated with this fee
            // Only check if the school_fee_id column exists in the payments table
          
                $hasPayments = $schoolFee->payments()->exists();
                if ($hasPayments) {
                    return back()->withErrors(['error' => 'Cannot delete school fee that has associated payments.']);
                }
         

            $schoolFee->delete();

            return redirect()->route('bursar.school-fees.index')
                ->with('success', 'School fee deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete school fee. It may be referenced by other records.']);
        }
    }
}
