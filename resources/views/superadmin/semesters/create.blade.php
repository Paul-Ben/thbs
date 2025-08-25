@extends('layouts.dashboard')

@section('title', 'Create New Semester')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Create New Semester
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

                    <form action="{{ route('superadmin.semesters.store') }}" method="POST">
                        @csrf
                        
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
                                           value="{{ old('semester_name') }}" 
                                           placeholder="e.g., First Semester, Second Semester"
                                           required>
                                    @error('semester_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> 
                                        Enter a descriptive name for the semester (e.g., "First Semester", "Second Semester")
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
                                                    {{ old('school_session_id') == $session->id ? 'selected' : '' }}
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
                                           value="{{ old('registration_start_date') }}">
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
                                           value="{{ old('registration_end_date') }}">
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
                                               {{ old('is_current') ? 'checked' : '' }}>
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

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-lightbulb"></i> Quick Tips:</h6>
                                    <ul class="mb-0">
                                        <li>Semester names should be descriptive and follow your institution's naming convention</li>
                                        <li>Each semester must be associated with a school session</li>
                                        <li>Registration dates are optional but recommended for automated course registration control</li>
                                        <li>Only one semester can be marked as "current" at a time</li>
                                        <li>You can change the current semester status later from the semester list</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('superadmin.semesters.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Semester
                            </button>
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
    
    // Auto-select current session if no selection made
    if (!$('#school_session_id').val()) {
        $('#school_session_id option[data-current="true"]').prop('selected', true);
    }

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