@extends('layouts.dashboard')

@section('title', 'Semester Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Semester Details: {{ $semester->semester_name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.semesters.edit', $semester) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('superadmin.semesters.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-info"></i> Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">ID:</th>
                                            <td>{{ $semester->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Semester Name:</th>
                                            <td><strong>{{ $semester->semester_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>School Session:</th>
                                            <td>
                                                @if($semester->schoolSession)
                                                    <span class="badge bg-info fs-6">
                                                        {{ $semester->schoolSession->session_name }}
                                                    </span>
                                                    @if($semester->schoolSession->is_current)
                                                        <small class="text-success d-block mt-1">
                                                            <i class="fas fa-check-circle"></i> Current Session
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger">No Session Assigned</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
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
                                            <th>Registration Period:</th>
                                            <td>
                                                @if($semester->registration_start_date && $semester->registration_end_date)
                                                    <strong>Start:</strong> {{ $semester->registration_start_date->format('M d, Y') }}<br>
                                                    <strong>End:</strong> {{ $semester->registration_end_date->format('M d, Y') }}
                                                    
                                                    @php
                                                        $now = now();
                                                        $isActive = $now->between($semester->registration_start_date, $semester->registration_end_date);
                                                        $hasStarted = $now->gt($semester->registration_start_date);
                                                        $hasEnded = $now->gt($semester->registration_end_date);
                                                    @endphp
                                                    
                                                    <br>
                                                    <small class="mt-1 d-block">
                                                        @if($isActive)
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-clock"></i> Registration Open
                                                            </span>
                                                        @elseif(!$hasStarted)
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-hourglass-start"></i> Not Started
                                                            </span>
                                                        @elseif($hasEnded)
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-hourglass-end"></i> Registration Closed
                                                            </span>
                                                        @endif
                                                    </small>
                                                @else
                                                    <span class="text-muted">Not Set</span>
                                                    <br>
                                                    <small class="text-warning">
                                                        <i class="fas fa-exclamation-triangle"></i> 
                                                        Registration dates not configured
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h3 class="text-primary mb-1">{{ $stats['total_courses'] }}</h3>
                                                <small class="text-muted">Total Courses</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h3 class="text-info mb-1">{{ $stats['total_registrations'] }}</h3>
                                                <small class="text-muted">Registrations</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h3 class="text-warning mb-1">{{ $stats['total_results'] }}</h3>
                                                <small class="text-muted">Results</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h3 class="text-secondary mb-1">{{ $stats['days_since_created'] }}</h3>
                                                <small class="text-muted">Days Old</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <th>Created:</th>
                                            <td>{{ $semester->created_at->format('M d, Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>
                                                {{ $semester->updated_at->format('M d, Y \a\t h:i A') }}
                                                <small class="text-muted d-block">
                                                    ({{ $stats['days_since_updated'] }} days ago)
                                                </small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Data Section -->
                    @if($stats['total_courses'] > 0 || $stats['total_registrations'] > 0 || $stats['total_results'] > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="fas fa-link"></i> Related Data Overview</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @if($stats['total_courses'] > 0)
                                                <div class="col-md-4 mb-3">
                                                    <div class="alert alert-primary">
                                                        <h6><i class="fas fa-book"></i> Courses ({{ $stats['total_courses'] }})</h6>
                                                        <p class="mb-2">This semester has courses assigned to it.</p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle"></i> 
                                                            Students can register for these courses during the registration period.
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($stats['total_registrations'] > 0)
                                                <div class="col-md-4 mb-3">
                                                    <div class="alert alert-info">
                                                        <h6><i class="fas fa-user-plus"></i> Course Registrations ({{ $stats['total_registrations'] }})</h6>
                                                        <p class="mb-2">Students have registered for courses in this semester.</p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-warning text-warning"></i> 
                                                            Deleting this semester will affect these registrations.
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($stats['total_results'] > 0)
                                                <div class="col-md-4 mb-3">
                                                    <div class="alert alert-warning">
                                                        <h6><i class="fas fa-trophy"></i> Results ({{ $stats['total_results'] }})</h6>
                                                        <p class="mb-2">Academic results have been recorded for this semester.</p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-exclamation-triangle text-danger"></i> 
                                                            Critical: Results data will be lost if semester is deleted.
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    <h6><i class="fas fa-info-circle text-info"></i> No Related Data</h6>
                                    <p class="mb-0">
                                        This semester doesn't have any courses, registrations, or results yet. 
                                        You can safely modify or delete this semester without affecting any related data.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('superadmin.semesters.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to List
                                    </a>
                                </div>
                                <div>
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
                                    
                                    <a href="{{ route('superadmin.semesters.edit', $semester) }}" class="btn btn-warning me-2">
                                        <i class="fas fa-edit"></i> Edit Semester
                                    </a>
                                    
                                    @if($stats['total_courses'] == 0 && $stats['total_registrations'] == 0 && $stats['total_results'] == 0)
                                        <form action="{{ route('superadmin.semesters.destroy', $semester) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this semester? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i> Delete Semester
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-danger" disabled title="Cannot delete semester with related data">
                                            <i class="fas fa-trash"></i> Delete Semester
                                        </button>
                                    @endif
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