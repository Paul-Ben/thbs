<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\PaymentType;
use App\Models\Application;

class InitializePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'payment_type' => 'required|string|in:' . implode(',', PaymentType::ALL),
        ];

        // Different validation rules based on payment type
        if ($this->payment_type === PaymentType::APPLICATION_FEE) {
            $rules = array_merge($rules, [
                'email' => 'required|email|max:255',
                'surname' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'othernames' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'phone' => 'nullable|string|regex:/^[0-9+\-\s\(\)]+$/|max:20',
                'programme_id' => 'required|exists:programmes,id',
            ]);
        } elseif ($this->payment_type === PaymentType::APTITUDE_TEST_FEE) {
            $rules = array_merge($rules, [
                'application_number' => 'required|string|exists:applications,application_number',
            ]);
        }

        return $rules;
    }

    /**
     * Handle data enrichment after validation passes
     */
    protected function passedValidation(): void
    {
        if ($this->payment_type === PaymentType::APTITUDE_TEST_FEE && $this->has('application_number')) {
            $application = Application::where('application_number', $this->application_number)->first();
            
            if (!$application) {
                throw new \Exception('Application not found with the provided application number.');
            }

            // Merge application data into the request
            $this->merge([
                'application_id' => $application->id,
                'email' => $application->email,
                'surname' => $application->applicant_surname,
                'othernames' => $application->applicant_othernames,
                'phone' => $application->phone ?? 'N/A',
            ]);
        }
    }
}
