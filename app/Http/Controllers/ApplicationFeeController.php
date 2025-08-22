<?php

namespace App\Http\Controllers;

use App\Models\ApplicationFee;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class ApplicationFeeController extends Controller
{
    /**
     * Display a listing of application fees
     */
    public function index()
    {
        $authUser = Auth::user();
        $applicationFees = ApplicationFee::with('programme.department')->paginate(15);
        return view('bursar.application-fees.index', compact('authUser', 'applicationFees'));
    }

    /**
     * Show the form for creating a new application fee
     */
    public function create()
    {
        $authUser = Auth::user();
        $programmes = Programme::with('department')->get();
        return view('bursar.application-fees.create', compact('authUser', 'programmes'));
    }

    /**
     * Store a newly created application fee in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'programme_id' => 'required|exists:programmes,id|unique:application_fees,programme_id',
            'amount' => 'required|numeric|min:0|max:999999.99'
        ], [
            'programme_id.unique' => 'An application fee for this programme already exists.',
            'amount.min' => 'The amount must be at least 0.',
            'amount.max' => 'The amount cannot exceed 999,999.99.'
        ]);

        ApplicationFee::create([
            'programme_id' => $request->programme_id,
            'amount' => $request->amount
        ]);

        ToastMagic::success('Application fee created successfully!');
        return redirect()->route('bursar.application-fees.index');
    }

    /**
     * Display the specified application fee
     */
    public function show(ApplicationFee $applicationFee)
    {
        $authUser = Auth::user();
        $applicationFee->load('programme.department');
        return view('bursar.application-fees.show', compact('authUser', 'applicationFee'));
    }

    /**
     * Show the form for editing the specified application fee
     */
    public function edit(ApplicationFee $applicationFee)
    {
        $authUser = Auth::user();
        $programmes = Programme::with('department')->get();
        $applicationFee->load('programme.department');
        return view('bursar.application-fees.edit', compact('authUser', 'applicationFee', 'programmes'));
    }

    /**
     * Update the specified application fee in storage
     */
    public function update(Request $request, ApplicationFee $applicationFee)
    {
        $request->validate([
            'programme_id' => 'required|exists:programmes,id|unique:application_fees,programme_id,' . $applicationFee->id,
            'amount' => 'required|numeric|min:0|max:999999.99'
        ], [
            'programme_id.unique' => 'An application fee for this programme already exists.',
            'amount.min' => 'The amount must be at least 0.',
            'amount.max' => 'The amount cannot exceed 999,999.99.'
        ]);

        $applicationFee->update([
            'programme_id' => $request->programme_id,
            'amount' => $request->amount
        ]);

        ToastMagic::success('Application fee updated successfully!');
        return redirect()->route('bursar.application-fees.index');
    }

    /**
     * Remove the specified application fee from storage
     */
    public function destroy(ApplicationFee $applicationFee)
    {
        $programmeName = $applicationFee->programme->name;
        $applicationFee->delete();

        ToastMagic::success("Application fee for {$programmeName} deleted successfully!");
        return redirect()->route('bursar.application-fees.index');
    }
}