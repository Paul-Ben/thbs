@extends('layouts.dashboard')

@section('title', 'School Session Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">School Session Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.school-sessions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Sessions
                        </a>
                        <a href="{{ route('superadmin.school-sessions.edit', $schoolSession) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-calendar-alt"></i> Session Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Session ID:</strong></td>
                                    <td>{{ $schoolSession->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Session Name:</strong></td>
                                    <td>{{ $schoolSession->session_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Starting Year:</strong></td>
                                    <td>{{ $schoolSession->year }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($schoolSession->is_current)
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check-circle"></i> Current Session
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">
                                                <i class="fas fa-pause-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $schoolSession->created_at->format('F d, Y \a\t g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $schoolSession->updated_at->format('F d, Y \a\t g:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-list"></i> Related Semesters</h5>
                            @if($schoolSession->semesters->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Semester Name</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($schoolSession->semesters as $semester)
                                                <tr>
                                                    <td>{{ $semester->semester_name }}</td>
                                                    <td>
                                                        @if($semester->is_current)
                                                            <span class="badge bg-success">Current</span>
                                                        @else
                                                            <span class="badge bg-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $semester->created_at->format('M d, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    No semesters have been created for this session yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs"></i> Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="{{ route('superadmin.school-sessions.edit', $schoolSession) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Session
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if(!$schoolSession->is_current)
                                <div class="d-grid">
                                    <form action="{{ route('superadmin.school-sessions.set-current', $schoolSession) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success"
                                                onclick="return confirm('Are you sure you want to set this as the current session?')">
                                            <i class="fas fa-check-circle"></i> Set as Current
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="d-grid">
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check-circle"></i> Currently Active
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if($schoolSession->semesters->count() == 0)
                                <div class="d-grid">
                                    <form action="{{ route('superadmin.school-sessions.destroy', $schoolSession) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this session? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i> Delete Session
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="d-grid">
                                    <button class="btn btn-danger" disabled title="Cannot delete session with related semesters">
                                        <i class="fas fa-trash"></i> Cannot Delete
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Session Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3>{{ $schoolSession->semesters->count() }}</h3>
                                    <p class="mb-0">Total Semesters</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3>{{ $schoolSession->semesters->where('is_current', true)->count() }}</h3>
                                    <p class="mb-0">Active Semesters</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h3>{{ $schoolSession->created_at->diffInDays(now()) }}</h3>
                                    <p class="mb-0">Days Since Created</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h3>{{ $schoolSession->updated_at->diffInDays(now()) }}</h3>
                                    <p class="mb-0">Days Since Updated</p>
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
    .table-borderless td {
        border: none;
        padding: 0.5rem 0;
    }
    .card .card-body h3 {
        font-size: 2rem;
        font-weight: bold;
    }
</style>
@endpush