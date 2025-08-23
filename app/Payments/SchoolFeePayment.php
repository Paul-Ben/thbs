<?php

namespace App\Payments;

use App\Contracts\PaymentTypeInterface;
use App\Models\SchoolFee;
use App\Models\SchoolFeePayment as SchoolFeePaymentModel;
use App\Models\Student;
use App\Models\SchoolSession;
use App\Models\Semester;
use App\Constants\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;

class SchoolFeePayment implements PaymentTypeInterface
{
    public function validate(Request $request): void
    {
        
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'school_fee_id' => 'required|exists:school_fees,id',

        ]);

        // Verify the school fee belongs to the student's program and level
        $student = Auth::user()->student;
        if (!$student) {
            throw ValidationException::withMessages([
                'student' => 'Student record not found.'
            ]);
        }

        $schoolFee = SchoolFee::find($request->school_fee_id);
        if (!$schoolFee || !$schoolFee->is_active) {
            throw ValidationException::withMessages([
                'school_fee_id' => 'Invalid or inactive school fee.'
            ]);
        }

        // Check if fee belongs to student's program and level
        if ($schoolFee->programme_id !== $student->programme_id || 
            $schoolFee->level_id !== $student->level_id) {
            throw ValidationException::withMessages([
                'school_fee_id' => 'This fee is not applicable to your program or level.'
            ]);
        }

        // Check if fee is for current session and semester
        $currentSession = SchoolSession::where('is_current', true)->first();
        $currentSemester = Semester::where('is_current', true)->first();
        
        if (!$currentSession || !$currentSemester) {
            throw ValidationException::withMessages([
                'session' => 'No current session or semester is set.'
            ]);
        }

        if ($schoolFee->school_session_id !== $currentSession->id || 
            $schoolFee->semester_id !== $currentSemester->id) {
            throw ValidationException::withMessages([
                'school_fee_id' => 'This fee is not for the current session or semester.'
            ]);
        }

        // Check if student has already paid this fee
        $existingPayment = SchoolFeePaymentModel::where('student_id', $student->id)
            ->where('school_fee_id', $request->school_fee_id)
            ->where('status', PaymentStatus::SUCCESSFUL)
            ->first();

        if ($existingPayment) {
            throw ValidationException::withMessages([
                'school_fee_id' => 'You have already paid this fee.'
            ]);
        }
    }

    public function buildPaymentData(array $data): array
    {
        // If this is already processed payment data, return it as-is
        if (isset($data['amount']) && isset($data['meta'])) {
            return $data;
        }
        
        if (!isset($data['school_fee_id'])) {
            throw new \Exception('school_fee_id is required but missing from request data');
        }
        
        $student = Auth::user()->student;
        $schoolFee = SchoolFee::with(['programme', 'semester'])->find($data['school_fee_id']);
        
        return [
            'amount' => $schoolFee->amount,
            'title' => 'School Fee Payment - ' . $schoolFee->name,
            'description' => "Payment for {$schoolFee->name} - {$schoolFee->programme->name} - {$schoolFee->semester->name}",
            'meta' => [
                'payment_type' => 'school_fee',
                'student_id' => $student->id,
                'school_fee_id' => $schoolFee->id,
                'programme_id' => $student->programme_id,
                'level_id' => $student->level_id,
                'school_session_id' => $schoolFee->school_session_id,
                'semester_id' => $schoolFee->semester_id,
                'fee_name' => $schoolFee->name,
                'fee_type' => $schoolFee->fee_type,
            ],
            'email' => $data['email'],
            'name' => $data['name'],
            'phone' => $data['phone'],
        ];
    }

    public function handleSuccess(array $paymentResult): RedirectResponse
    {
        return redirect()->route('student.payments.history')
            ->with('success', 'Payment successful! Your fee payment has been processed.');
    }

    public function persistVerifiedPayment(array $data, string $txRef, array $meta): array
    {
        $student = Student::find($meta['student_id']);
        $schoolFee = SchoolFee::find($meta['school_fee_id']);

        // Create or update the school fee payment record
        $payment = SchoolFeePaymentModel::updateOrCreate(
            [
                'student_id' => $student->id,
                'school_fee_id' => $schoolFee->id,
                'reference' => $txRef,
            ],
            [
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'NGN',
                'payment_method' => 'flutterwave',
                'transaction_id' => $data['id'],
                'status' => PaymentStatus::SUCCESSFUL,
                'payment_date' => now(),
                'description' => "Payment for {$schoolFee->name}",
                'metadata' => [
                    'flutterwave_data' => $data,
                    'fee_details' => [
                        'fee_name' => $meta['fee_name'],
                        'fee_type' => $meta['fee_type'],
                        'programme_id' => $meta['programme_id'],
                        'level_id' => $meta['level_id'],
                        'school_session_id' => $meta['school_session_id'],
                        'semester_id' => $meta['semester_id'],
                    ]
                ]
            ]
        );

        return [
            'status' => PaymentStatus::SUCCESSFUL,
            'payment_model' => $payment
        ];
    }
}