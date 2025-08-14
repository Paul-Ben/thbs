<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ApplicationService
{
    public function create(array $data, string $txRef): Application
    {

        $data['application_number'] = $this->generateAppNumber();
        $data['status'] = 'pending';
        $data['is_filled'] = 1;
        $data['declaration_check'] = $data['declaration_check'] ?? 'false';

        if (isset($data['passport'])) {
            $timestamp = now()->timestamp;
            $othernames = isset($data['applicant_othernames']) ? $data['applicant_othernames'] : 'passport';
            $filename = $othernames . '_' . $timestamp;
            $data['passport'] = $this->handleUpload($data['passport'], 'applicant-passport', $filename);
        }

        if (isset($data['credential'])) {
            $credOthernames = isset($data['applicant_othernames']) ? $data['applicant_othernames'] : 'credential';
            $credFilename = $credOthernames . '_' . time();
            $data['credential'] = $this->handleUpload($data['credential'], 'application-credentials', $credFilename);
        }

        return DB::transaction(function () use ($data, $txRef) {
            $application = Application::updateOrCreate(['payment_reference' => $txRef], $data);
            $this->storeAcademicRecords($application, $data);
            return $application;
        });
    }

    public function handleUpload($file, string $folder, ?string $filename = null): string
    {
        $extension = $file->getClientOriginalExtension();

        if ($filename) {
            $safeFilename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename);
            $storeName = $safeFilename . '.' . $extension;
            return $file->storeAs($folder, $storeName, 'public');
        }

        return $file->store($folder, 'public');
    }

    public function printoutPdfDownload(Application $application): array
    {
        $application->load('academicRecords');
        $oLevelRecords = $application->academicRecords->where('level', 'O/LEVEL')->values();
        $aLevelRecords = $application->academicRecords->where('level', 'A/LEVEL')->values();
        $pdf = DomPdf::loadView('application.printout', [
            'application' => $application,
            'oLevelRecords' => $oLevelRecords,
            'aLevelRecords' => $aLevelRecords,
            'isPdf' => true,
        ]);
        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $application->applicant_othernames) . '_application_printout.pdf';
        return ['pdf' => $pdf, 'filename' => $filename];
    }

    protected function storeAcademicRecords(Application $application, array $data): void
    {

        if (isset($data['olevel_school'])) {
            $count = count($data['olevel_school']);
            for ($i = 0; $i < $count; $i++) {
                if (
                    !empty($data['olevel_school'][$i]) ||
                    !empty($data['olevel_exam_type'][$i]) ||
                    !empty($data['olevel_exam_year'][$i]) ||
                    !empty($data['olevel_subjects'][$i]) ||
                    !empty($data['olevel_grade'][$i])
                ) {
                    $application->academicRecords()->create([
                        'level' => 'O/LEVEL',
                        'school_name' => $data['olevel_school'][$i] ?? null,
                        'exam_type' => $data['olevel_exam_type'][$i] ?? null,
                        'exam_year' => $data['olevel_exam_year'][$i] ?? null,
                        'subject' => $data['olevel_subjects'][$i] ?? null,
                        'grade' => $data['olevel_grade'][$i] ?? null,
                        'number_of_sittings' => $data['olevel_sittings'][$i] ?? null,
                    ]);
                }
            }
        }

        if (isset($data['alevel_qualification'])) {
            $count = count($data['alevel_qualification']);
            for ($i = 0; $i < $count; $i++) {
                if (
                    !empty($data['alevel_qualification'][$i]) ||
                    !empty($data['alevel_graduation_year'][$i]) ||
                    !empty($data['alevel_certificate'][$i]) ||
                    !empty($data['alevel_grade'][$i])
                ) {
                    $application->academicRecords()->create([
                        'level' => 'A/LEVEL',
                        'other_qualification' => $data['alevel_qualification'][$i] ?? null,
                        'graduation_year' => $data['alevel_graduation_year'][$i] ?? null,
                        'certificate_obtained' => $data['alevel_certificate'][$i] ?? null,
                        'alevel_grade' => $data['alevel_grade'][$i] ?? null,
                    ]);
                }
            }
        }
    }


    public function generateAppNumber(): string
    {
        $randomNumber = rand(100000, 999999);
        $year = date('Y');
        return "BSUTH-{$randomNumber}-{$year}";
    }
}
