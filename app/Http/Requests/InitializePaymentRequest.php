<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\PaymentType;

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
}
