@extends('admission_officer.layout')
@section('content')
<div class="container-fluid px-0">
    <div class="row gx-0">
        <div class="col-md-12">
            <div class="card py-0">
                <div class="d-flex align-items-center ps-4" style="height: 80px;">
                    <div class="page-header mb-0">
                        <ul class="breadcrumbs mb-0">
                            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                            <li class="separator"><i class="icon-arrow-right"></i></li>
                            <li class="nav-item"><a href="#">Admission Management</a></li>
                            <li class="separator"><i class="icon-arrow-right"></i></li>
                            <li class="nav-item"><a href="{{ route('admission_officer.admissions') }}">Admissions</a></li>
                            <li class="separator"><i class="icon-arrow-right"></i></li>
                            <li class="nav-item"><a href="#">Admitted Student</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Applicant Details</h4>
                <div>
                    <a href="{{ route('admission_officer.admissions') }}" class="btn btn-outline-secondary btn-sm">Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Application No.</label>
                        <div class="fw-semibold">{{ $admission->application_number }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Admission Status</label>
                        <div>
                            <span class="badge bg-{{ $admission->status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($admission->status) }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Surname</label>
                        <div class="fw-semibold">{{ $admission->applicant_surname }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Other Names</label>
                        <div class="fw-semibold">{{ $admission->applicant_othernames }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Date of Birth</label>
                        <div class="fw-semibold">{{ $admission->date_of_birth }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Gender</label>
                        <div class="fw-semibold">{{ $admission->gender }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small mb-1">State of Origin</label>
                        <div class="fw-semibold">{{ $admission->state_of_origin }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">LGA</label>
                        <div class="fw-semibold">{{ $admission->lga }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Nationality</label>
                        <div class="fw-semibold">{{ $admission->nationality }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Religion</label>
                        <div class="fw-semibold">{{ $admission->religion }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Marital Status</label>
                        <div class="fw-semibold">{{ $admission->marital_status }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Home Town</label>
                        <div class="fw-semibold">{{ $admission->home_town }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Email</label>
                        <div class="fw-semibold">{{ $admission->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Phone</label>
                        <div class="fw-semibold">{{ $admission->phone }}</div>
                    </div>

                    <div class="col-12">
                        <label class="text-muted small mb-1">Correspondence Address</label>
                        <div class="fw-semibold">{{ $admission->correspondence_address }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small mb-1">Permanent Home Address</label>
                        <div class="fw-semibold">{{ $admission->permanent_home_address }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Parent / Guardian</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Name</label>
                        <div class="fw-semibold">{{ $admission->parent_guardian_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Phone</label>
                        <div class="fw-semibold">{{ $admission->parent_guardian_phone }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small mb-1">Address</label>
                        <div class="fw-semibold">{{ $admission->parent_guardian_address }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small mb-1">Occupation</label>
                        <div class="fw-semibold">{{ $admission->parent_guardian_occupation }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Programme & Session</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted small">Programme</div>
                    <div class="fw-semibold">{{ optional($admission->programme)->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Application Session</div>
                    <div class="fw-semibold">{{ optional($admission->applicationSession)->session_name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Payment Reference</div>
                    <div class="fw-semibold">{{ $admission->payment_reference ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Actions</h4>
            </div>
            <div class="card-body d-flex gap-2 flex-wrap">
                <a href="{{ route('admission_officer.admissions') }}" class="btn btn-secondary btn-sm">Back to list</a>
                {{-- Add more action buttons here if needed --}}
            </div>
        </div>
    </div>
</div>
@endsection
