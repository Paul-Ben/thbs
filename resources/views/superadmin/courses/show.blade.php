@extends('layouts.dashboard')

@section('title', 'Course Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-book"></i> Course Details
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.courses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Course Code:</label>
                                <p class="form-control-static"><strong>{{ $course->code }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Course Title:</label>
                                <p class="form-control-static">{{ $course->title }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Credit Units:</label>
                                <p class="form-control-static">
                                    <span class="badge bg-success fs-6">
                                        {{ $course->credit_units }} {{ $course->credit_units == 1 ? 'Unit' : 'Units' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Programme:</label>
                                <p class="form-control-static">
                                    <span class="badge bg-info fs-6">{{ $course->programme->name }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Level:</label>
                                <p class="form-control-static">
                                    <span class="badge bg-secondary fs-6">{{ $course->level->name }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Semester:</label>
                                <p class="form-control-static">
                                    <span class="badge bg-primary fs-6">{{ $course->semester->semester_name }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Created At:</label>
                                <p class="form-control-static">{{ $course->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Updated:</label>
                                <p class="form-control-static">{{ $course->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('superadmin.courses.edit', $course) }}" class="btn btn-warning me-2">
                                    <i class="fas fa-edit"></i> Edit Course
                                </a>
                                <form action="{{ route('superadmin.courses.destroy', $course) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this course? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete Course
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
@endsection