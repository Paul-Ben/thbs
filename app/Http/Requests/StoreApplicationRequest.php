<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
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
        return [
            'applicant_surname' => 'required|string|max:255',
            'applicant_othernames' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'state_of_origin' => 'required|string',
            'lga' => 'required|string',
            'nationality' => 'required|string',
            'religion' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'home_town' => 'nullable|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'correspondence_address' => 'nullable|string',
            'employment_status' => 'nullable|string',
            'permanent_home_address' => 'nullable|string',
            'parent_guardian_name' => 'nullable|string',
            'parent_guardian_phone' => 'nullable|string',
            'parent_guardian_address' => 'nullable|string',
            'parent_guardian_occupation' => 'nullable|string',
            'programme_id' => 'nullable',
            'passport' => 'required|image|max:5048',
            'declaration_check' => 'accepted',
            
            'credential' => 'required|file|mimes:pdf|max:5048',
            // O/LEVEL Academic Records
            'olevel_school' => 'array',
            'olevel_school.*' => 'nullable|string|max:255',
            'olevel_exam_type' => 'array',
            'olevel_exam_type.*' => 'nullable|string|max:255',
            'olevel_exam_year' => 'array',
            'olevel_exam_year.*' => 'nullable|string|max:255',
            'olevel_subjects' => 'array',
            'olevel_subjects.*' => 'nullable|string|max:255',
            'olevel_grade' => 'array',
            'olevel_grade.*' => 'nullable|string|max:255',
            'olevel_sittings' => 'array',
            'olevel_sittings.*' => 'nullable|string|max:255',

            // A/LEVEL Academic Records
            'alevel_qualification' => 'array',
            'alevel_qualification.*' => 'nullable|string|max:255',
            'alevel_graduation_year' => 'array',
            'alevel_graduation_year.*' => 'nullable|string|max:255',
            'alevel_certificate' => 'array',
            'alevel_certificate.*' => 'nullable|string|max:255',
            'alevel_grade' => 'array',
            'alevel_grade.*' => 'nullable|string|max:255',
        ];
    }
}
