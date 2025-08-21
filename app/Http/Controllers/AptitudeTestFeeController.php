<?php

namespace App\Http\Controllers;

use App\Models\AptitudeTestFee;
use App\Models\AptitudeTestPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AptitudeTestFeeController extends Controller
{
    /**
     * Display a listing of the aptitude test fees.
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $query = AptitudeTestFee::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $aptitudeTestFees = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('bursar.aptitude_test_fees.index', compact('aptitudeTestFees', 'authUser'));
    }

    /**
     * Show the form for creating a new aptitude test fee.
     */
    public function create()
    {
        $authUser = Auth::user();
        return view('bursar.aptitude_test_fees.create', compact('authUser'));
    }

    /**
     * Store a newly created aptitude test fee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0|max:999999.99',
            'currency' => 'required|string|size:3',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        // If this fee is being set as active, deactivate all other fees
        if ($validated['is_active'] ?? false) {
            AptitudeTestFee::where('is_active', true)->update(['is_active' => false]);
        }

        $validated['is_active'] = $validated['is_active'] ?? false;

        AptitudeTestFee::create($validated);

        return redirect()->route('bursar.aptitude-test-fees.index')
                        ->with('success', 'Aptitude test fee created successfully.');
    }

    /**
     * Display the specified aptitude test fee.
     */
    public function show(AptitudeTestFee $aptitudeTestFee)
    {
        $authUser = Auth::user();
        
        // Since payments are linked to applications, not directly to fees,
        // and we now have a single fee for all programs, get all aptitude test payments
        $totalPayments = AptitudeTestPayment::count();
        $successfulPayments = AptitudeTestPayment::where('status', 'successful')->count();
        $totalRevenue = AptitudeTestPayment::where('status', 'successful')->sum('amount');
        
        // Get recent payments for display
        $aptitudeTestFee->aptitudeTestPayments = AptitudeTestPayment::with('application.user')
            ->latest()
            ->take(10)
            ->get();
        
        return view('bursar.aptitude_test_fees.show', compact(
            'aptitudeTestFee', 
            'totalPayments', 
            'successfulPayments', 
            'totalRevenue',
            'authUser'
        ));
    }

    /**
     * Show the form for editing the specified aptitude test fee.
     */
    public function edit(AptitudeTestFee $aptitudeTestFee)
    {
        $authUser = Auth::user();
        
        // Get payment statistics for display (if needed in edit view)
        $totalPayments = AptitudeTestPayment::count();
        $successfulPayments = AptitudeTestPayment::where('status', 'successful')->count();
        $totalRevenue = AptitudeTestPayment::where('status', 'successful')->sum('amount');
        
        return view('bursar.aptitude_test_fees.edit', compact('aptitudeTestFee', 'authUser', 'totalPayments', 'successfulPayments', 'totalRevenue'));
    }

    /**
     * Update the specified aptitude test fee in storage.
     */
    public function update(Request $request, AptitudeTestFee $aptitudeTestFee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0|max:999999.99',
            'currency' => 'required|string|size:3',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        // If this fee is being set as active, deactivate all other fees
        if ($validated['is_active'] ?? false) {
            AptitudeTestFee::where('is_active', true)
                          ->where('id', '!=', $aptitudeTestFee->id)
                          ->update(['is_active' => false]);
        }

        $validated['is_active'] = $validated['is_active'] ?? false;

        $aptitudeTestFee->update($validated);

        return redirect()->route('bursar.aptitude-test-fees.index')
                        ->with('success', 'Aptitude test fee updated successfully.');
    }

    /**
     * Remove the specified aptitude test fee from storage.
     */
    public function destroy(AptitudeTestFee $aptitudeTestFee)
    {
        // Check if there are any payments in the system (since we have a single fee structure)
        if (AptitudeTestPayment::exists()) {
            return redirect()->route('bursar.aptitude-test-fees.index')
                           ->with('error', 'Cannot delete aptitude test fee with existing payments.');
        }

        $aptitudeTestFee->delete();

        return redirect()->route('bursar.aptitude-test-fees.index')
                        ->with('success', 'Aptitude test fee deleted successfully.');
    }

    /**
     * Toggle the active status of the specified aptitude test fee.
     */
    public function toggleStatus(AptitudeTestFee $aptitudeTestFee)
    {
        // If activating this fee, deactivate all other fees
        if (!$aptitudeTestFee->is_active) {
            AptitudeTestFee::where('is_active', true)
                          ->where('id', '!=', $aptitudeTestFee->id)
                          ->update(['is_active' => false]);
        }

        $aptitudeTestFee->update([
            'is_active' => !$aptitudeTestFee->is_active
        ]);

        $status = $aptitudeTestFee->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('bursar.aptitude-test-fees.index')
                        ->with('success', "Aptitude test fee {$status} successfully.");
    }
}
