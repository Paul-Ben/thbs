@extends('layouts.dashboard')

@section('title', 'Semester Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt"></i> Semester Details: {{ $semester->semester_name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.semesters.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle"></i> Basic Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Semester ID:</strong></td>
                                            <td>{{ $semester->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Semester Name:</strong></td>
                                            <td>{{ $semester->semester_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>School Session:</strong></td>
                                            <td>
                                                <span class="badge bg-info fs-6">
                                                    {{ $semester->session->session_name }}
                                                </span>
                                                @if($semester->session->is_current)
                                                    <span class="badge bg-success ms-1">Current Session</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($semester->is_current)
                                                    <span class="badge bg-success fs-6">
                                                        <i class="fas fa-check-circle"></i> Current Semester
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary fs-6">
                                                        <i class="fas fa-circle"></i> Inactive
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $semester->created_at->format('F d, Y \a\t g:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Updated:</strong></td>
                                            <td>{{ $semester->updated_at->format('F d, Y \a\t g:i A') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-bar"></i> Statistics
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h4 class="text-primary mb-1">{{ $stats['total_courses'] }}</h4>
                                                <small class="text-muted">Total Courses</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h4 class="text-info mb-1">{{ $stats['total_registrations'] }}</h4>
                                                <small class="text-muted">Course Registrations</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h4 class="text-success mb-1">{{ $stats['total_results'] }}</h4>
                                                <small class="text-muted">Results Recorded</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h4 class="text-warning mb-1">{{ $stats['days_since_created'] }}</h4>
                                                <small class="text-muted">Days Since Created</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Courses -->
                    @if($semester->courses->count() > 0)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-book"></i> Associated Courses ({{ $semester->courses->count() }})
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Course Code</th>
                                                        <th>Course Title</th>
                                                        <th>Programme</th>
                                                        <th>Level</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($semester->courses->take(10) as $course)
                                                        <tr>
                                                            <td><code>{{ $course->code }}</code></td>
                                                            <td>{{ $course->title }}</td>
                                                            <td>{{ $course->programme->name ?? 'N/A' }}</td>
                                                            <td>{{ $course->level->name ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if($semester->courses->count() > 10)
                                                <p class="text-muted text-center mt-2">
                                                    <i class="fas fa-info-circle"></i> 
                                                    Showing first 10 courses. Total: {{ $semester->courses->count() }} courses.
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('superadmin.semesters.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-list"></i> Back to List
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('superadmin.semesters.edit', $semester) }}" class="btn btn-warning me-2">
                                        <i class="fas fa-edit"></i> Edit Semester
                                    </a>
                                    @if(!$semester->is_current)
                                        <form action="{{ route('superadmin.semesters.set-current', $semester) }}" 
                                              method="POST" class="d-inline me-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success" 
                                                    onclick="return confirm('Are you sure you want to set this semester as current?')">
                                                <i class="fas fa-check"></i> Set as Current
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('superadmin.semesters.destroy', $semester) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete this semester? This action cannot be undone and will affect all related data.')">
                                            <i class="fas fa-trash"></i> Delete Semester
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-title {
    font-size: 1.1rem;
}

.table td {
    padding: 0.5rem;
}

.border {
    border: 1px solid #dee2e6 !important;
}

.rounded {
    border-radius: 0.375rem !important;
}
</style>
@endpush