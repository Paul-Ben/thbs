@extends('layouts.dashboard')

@section('title', 'Create School Session')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New School Session</h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.school-sessions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Sessions
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

                    <form action="{{ route('superadmin.school-sessions.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="session_name" class="form-label">Session Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('session_name') is-invalid @enderror" 
                                           id="session_name" 
                                           name="session_name" 
                                           value="{{ old('session_name') }}"
                                           placeholder="e.g., 2024/2025"
                                           required>
                                    @error('session_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Enter the session name in format: YYYY/YYYY (e.g., 2024/2025)
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="year" class="form-label">Starting Year <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('year') is-invalid @enderror" 
                                           id="year" 
                                           name="year" 
                                           value="{{ old('year', date('Y')) }}"
                                           min="2000" 
                                           max="2100"
                                           required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Enter the starting year of the academic session
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="is_current" 
                                               name="is_current" 
                                               value="1"
                                               {{ old('is_current') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_current">
                                            Set as Current Session
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> 
                                        If checked, this will become the active session and any previously current session will be deactivated.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('superadmin.school-sessions.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Session
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar-alt"></i> Session Naming</h6>
                            <p class="text-muted small">
                                School sessions should follow the format YYYY/YYYY representing the academic year.
                                For example: 2024/2025 represents the academic year starting in 2024 and ending in 2025.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-check-circle"></i> Current Session</h6>
                            <p class="text-muted small">
                                Only one session can be marked as current at a time. The current session is used 
                                for new registrations and active academic activities.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate session name based on year
    $('#year').on('input', function() {
        const year = parseInt($(this).val());
        if (year && year >= 2000 && year <= 2100) {
            const nextYear = year + 1;
            $('#session_name').val(year + '/' + nextYear);
        }
    });
    
    // Trigger on page load if year is already filled
    if ($('#year').val()) {
        $('#year').trigger('input');
    }
});
</script>
@endpush