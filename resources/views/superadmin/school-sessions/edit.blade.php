@extends('layouts.dashboard')

@section('title', 'Edit School Session')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit School Session</h3>
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

                    <form action="{{ route('superadmin.school-sessions.update', $schoolSession) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="session_name" class="form-label">Session Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('session_name') is-invalid @enderror" 
                                           id="session_name" 
                                           name="session_name" 
                                           value="{{ old('session_name', $schoolSession->session_name) }}"
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
                                           value="{{ old('year', $schoolSession->year) }}"
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
                                               {{ old('is_current', $schoolSession->is_current) ? 'checked' : '' }}>
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
                                        <i class="fas fa-save"></i> Update Session
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Session Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Session Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-calendar-alt"></i> Current Details</h6>
                            <p class="text-muted small mb-1"><strong>Session:</strong> {{ $schoolSession->session_name }}</p>
                            <p class="text-muted small mb-1"><strong>Year:</strong> {{ $schoolSession->year }}</p>
                            <p class="text-muted small mb-1">
                                <strong>Status:</strong> 
                                @if($schoolSession->is_current)
                                    <span class="badge bg-success">Current</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                            <p class="text-muted small mb-0"><strong>Created:</strong> {{ $schoolSession->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-list"></i> Related Data</h6>
                            <p class="text-muted small mb-1">
                                <strong>Semesters:</strong> 
                                <span class="badge bg-info">{{ $schoolSession->semesters()->count() }}</span>
                            </p>
                            @if($schoolSession->semesters()->count() > 0)
                                <div class="mt-2">
                                    <small class="text-muted">Semesters:</small>
                                    @foreach($schoolSession->semesters as $semester)
                                        <div class="small text-muted">• {{ $semester->semester_name }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-exclamation-triangle"></i> Important Notes</h6>
                            <p class="text-muted small">
                                • Only one session can be current at a time<br>
                                • Changing the current session affects system-wide operations<br>
                                • Sessions with related data cannot be deleted
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
});
</script>
@endpush