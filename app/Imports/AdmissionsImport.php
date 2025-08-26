<?php

namespace App\Imports;

use App\Models\Admission;
use App\Models\Programme;
use App\Models\ApplicationSession;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class AdmissionsImport implements ToCollection, WithHeadingRow
{
    protected int $successCount = 0;
    protected int $errorCount = 0;
    protected array $errors = [];

    protected array $requiredHeaders = [
        'application_number','payment_reference','applicant_surname','applicant_othernames','date_of_birth','gender','state_of_origin','lga','nationality','religion','marital_status','home_town','email','phone','correspondence_address','employment_status','permanent_home_address','parent_guardian_name','parent_guardian_phone','parent_guardian_address','parent_guardian_occupation','programme_name','application_session','admission_status'
    ];

    public function getSuccessCount(): int { return $this->successCount; }
    public function getErrorCount(): int { return $this->errorCount; }
    public function getErrors(): array { return $this->errors; }
    public function getFirstError(): ?string { return $this->errors[0] ?? null; }
    public function hasErrors(): bool { return $this->errorCount > 0; }
    public function summary(): string {
        if ($this->hasErrors()) {
            return "Imported {$this->successCount} row(s); {$this->errorCount} row(s) failed.";
        }
        return "Imported {$this->successCount} row(s) successfully.";
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) 
        {
            try {
                // Normalize row to array and trim strings
                $data = is_array($row) ? $row : $row->toArray();
                $data = array_map(function ($v) {
                    return is_string($v) ? trim($v) : $v;
                }, $data);

                // Header validation for first processed row
                if ($index === 0) {
                    $presentHeaders = array_keys($data);
                    $missing = array_diff($this->requiredHeaders, $presentHeaders);
                    if (!empty($missing)) {
                        $this->errorCount++;
                        $this->errors[] = 'Missing required headers: '.implode(', ', $missing);
                        // Continue processing rows anyway
                    }
                }

                $programme = Programme::where('name', $data['programme_name'] ?? null)->first();
                $applicationSession = ApplicationSession::where('session_name', $data['application_session'] ?? null)->first();

                // Parse date_of_birth (supports Excel serials and strings)
                $dob = $data['date_of_birth'] ?? null;
                if ($dob !== null && $dob !== '') {
                    if (is_numeric($dob)) {
                        $dob = ExcelDate::excelToDateTimeObject($dob)->format('Y-m-d');
                    } else {
                        $dob = Carbon::parse($dob)->format('Y-m-d');
                    }
                } else {
                    $dob = null;
                }

                if (!empty($data['application_number']) && Admission::where('application_number', $data['application_number'])->exists()) {
                    throw new \RuntimeException('Duplicate application_number: '.$data['application_number']);
                }
                if (!empty($data['email']) && Admission::where('email', $data['email'])->exists()) {
                    throw new \RuntimeException('Duplicate email: '.$data['email']);
                }
                if (!empty($data['payment_reference']) && Admission::where('payment_reference', $data['payment_reference'])->exists()) {
                    throw new \RuntimeException('Duplicate payment_reference: '.$data['payment_reference']);
                }

                Admission::create([
                    'application_number' => $data['application_number'] ?? null,
                    'payment_reference' => $data['payment_reference'] ?? null,
                    'applicant_surname' => $data['applicant_surname'] ?? null,
                    'applicant_othernames' => $data['applicant_othernames'] ?? null,
                    'date_of_birth' => $dob,
                    'gender' => $data['gender'] ?? null,
                    'state_of_origin' => $data['state_of_origin'] ?? null,
                    'lga' => $data['lga'] ?? null,
                    'nationality' => $data['nationality'] ?? null,
                    'religion' => $data['religion'] ?? null,
                    'marital_status' => $data['marital_status'] ?? null,
                    'home_town' => $data['home_town'] ?? null,
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'correspondence_address' => $data['correspondence_address'] ?? null,
                    'employment_status' => $data['employment_status'] ?? null,
                    'permanent_home_address' => $data['permanent_home_address'] ?? null,
                    'parent_guardian_name' => $data['parent_guardian_name'] ?? null,
                    'parent_guardian_phone' => $data['parent_guardian_phone'] ?? null,
                    'parent_guardian_address' => $data['parent_guardian_address'] ?? null,
                    'parent_guardian_occupation' => $data['parent_guardian_occupation'] ?? null,
                    'programme_id' => $programme ? $programme->id : null,
                    'application_session_id' => $applicationSession ? $applicationSession->id : null,
                    'status' => $data['admission_status'] ?? 'pending',
                ]);
                $this->successCount++;
            } catch (\Throwable $e) {
                Log::warning('Admission import row failed', [
                    'row_index' => $index + 2, // +2 accounts for 1-based rows and header row
                    'error' => $e->getMessage(),
                ]);
                $this->errorCount++;
                $this->errors[] = 'Row '.($index + 2).': '.$e->getMessage();
                continue;
            }
        }
    }
}
