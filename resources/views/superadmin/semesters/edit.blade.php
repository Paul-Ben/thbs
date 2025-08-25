@extends('layouts.dashboard')

@section('title', 'Edit Semester')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Edit Semester: {{ $semester->semester_name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.semesters.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Current Semester Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Current Semester Information:</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>ID:</strong> {{ $semester->id }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Current Status:</strong> 
                                        @if($semester->is_current)
                                            <span class="badge bg-success">Current</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Created:</strong> {{ $semester->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Updated:</strong> {{ $semester->updated_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>School Session:</strong> 
                                        @if($semester->schoolSession)
                                            {{ $semester->schoolSession->session_name }}
                                        @else
                                            <span class="text-danger">No Session Assigned</span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Registration Period:</strong>
                                        @if($semester->registration_start_date && $semester->registration_end_date)
                                            {{ $semester->registration_start_date->format('M d, Y') }} - {{ $semester->registration_end_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not Set</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('superadmin.semesters.update', $semester) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="semester_name" class="form-label">
                                        <i class="fas fa-calendar-alt"></i> Semester Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('semester_name') is-invalid @enderror" 
                                           id="semester_name" 
                                           name="semester_name" 
                                           value="{{ old('semester_name', $semester->semester_name) }}" 
                                           placeholder="e.g., First Semester, Second Semester"
                                           required>
                                    @error('semester_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> 
                                        Enter a descriptive name for the semester
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="school_session_id" class="form-label">
                                        <i class="fas fa-graduation-cap"></i> School Session <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('school_session_id') is-invalid @enderror" 
                                            id="school_session_id" 
                                            name="school_session_id" 
                                            required>
                                        <option value="">Select School Session</option>
                                        @foreach($schoolSessions as $session)
                                            <option value="{{ $session->id }}" 
                                                    {{ old('school_session_id', $semester->school_session_id) == $session->id ? 'selected' : '' }}
                                                    {{ $session->is_current ? 'data-current="true"' : '' }}>
                                                {{ $session->session_name }}
                                                @if($session->is_current)
                                                    (Current)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_session_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> 
                                        Select the school session this semester belongs to
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Registration Period Fields --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="registration_start_date" class="form-label">
                                        <i class="fas fa-calendar-plus"></i> Registration Start Date
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('registration_start_date') is-invalid @enderror" 
                                           id="registration_start_date" 
                                           name="registration_start_date" 
                                           value="{{ old('registration_start_date', $semester->registration_start_date?->format('Y-m-d')) }}">
                                    @error('registration_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> 
                                        When students can start registering for courses
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="registration_end_date" class="form-label">
                                        <i class="fas fa-calendar-minus"></i> Registration End Date
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('registration_end_date') is-invalid @enderror" 
                                           id="registration_end_date" 
                                           name="registration_end_date" 
                                           value="{{ old('registration_end_date', $semester->registration_end_date?->format('Y-m-d')) }}">
                                    @error('registration_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> 
                                        When course registration closes
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_current" 
                                               name="is_current" 
                                               value="1"
                                               {{ old('is_current', $semester->is_current) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_current">
                                            <i class="fas fa-star"></i> Set as Current Semester
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-exclamation-triangle text-warning"></i> 
                                        <strong>Note:</strong> Setting this semester as current will automatically unset any other current semester.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Related Data Information -->
                        @if($semester->courses->count() > 0 || $semester->courseRegistrations->count() > 0)
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-exclamation-triangle"></i> Related Data:</h6>
                                        <p class="mb-2">This semester has the following related data:</p>
                                        <ul class="mb-0">
                                            @if($semester->courses->count() > 0)
                                                <li><strong>{{ $semester->courses->count() }}</strong> course(s) associated</li>
                                            @endif
                                            @if($semester->courseRegistrations->count() > 0)
                                                <li><strong>{{ $semester->courseRegistrations->count() }}</strong> course registration(s)</li>
                                            @endif
                                        </ul>
                                        <p class="mt-2 mb-0">
                                            <i class="fas fa-info-circle"></i> 
                                            <strong>Important:</strong> Changing the school session may affect related data. Please ensure this change is intentional.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('superadmin.semesters.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <div>
                                <a href="{{ route('superadmin.semesters.show', $semester) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Semester
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Highlight current session in dropdown
    $('#school_session_id option[data-current="true"]').css({
        'background-color': '#d4edda',
        'font-weight': 'bold'
    });

    // Date validation
    $('#registration_start_date').on('change', function() {
        const startDate = $(this).val();
        if (startDate) {
            $('#registration_end_date').attr('min', startDate);
        }
    });

    $('#registration_end_date').on('change', function() {
        const endDate = $(this).val();
        const startDate = $('#registration_start_date').val();
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            alert('Registration end date must be after start date');
            $(this).val('');
        }
    });
});
</script>
@endpush