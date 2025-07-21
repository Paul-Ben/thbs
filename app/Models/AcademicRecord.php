<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_id',
        'level',
        'school_name',
        'exam_type',
        'exam_year',
        'subject',
        'grade',
        'number_of_sittings',
        'other_qualification',
        'graduation_year',
        'certificate_obtained',
        'alevel_grade'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
