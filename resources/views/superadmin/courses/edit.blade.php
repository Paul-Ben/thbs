@extends('layouts.dashboard')

@section('title', 'Edit Course')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Edit Course
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.courses.index') }}" class="btn btn-secondary btn-sm">
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

                    <form action="{{ route('superadmin.courses.update', $course) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">
                                        <i class="fas fa-code"></i> Course Code <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code', $course->code) }}" 
                                           placeholder="e.g., CSC101, MTH201"
                                           required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-heading"></i> Course Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $course->title) }}" 
                                           placeholder="e.g., Introduction to Computer Science"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="programme_id" class="form-label">
                                        <i class="fas fa-graduation-cap"></i> Programme <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('programme_id') is-invalid @enderror" 
                                            id="programme_id" 
                                            name="programme_id" 
                                            required>
                                        <option value="">Select Programme</option>
                                        @foreach($programmes as $programme)
                                            <option value="{{ $programme->id }}" 
                                                    {{ old('programme_id', $course->programme_id) == $programme->id ? 'selected' : '' }}>
                                                {{ $programme->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('programme_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="level_id" class="form-label">
                                        <i class="fas fa-layer-group"></i> Level <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('level_id') is-invalid @enderror" 
                                            id="level_id" 
                                            name="level_id" 
                                            required>
                                        <option value="">Select Level</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id }}" 
                                                    {{ old('level_id', $course->level_id) == $level->id ? 'selected' : '' }}>
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('level_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="semester_id" class="form-label">
                                        <i class="fas fa-calendar-alt"></i> Semester <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('semester_id') is-invalid @enderror" 
                                            id="semester_id" 
                                            name="semester_id" 
                                            required>
                                        <option value="">Select Semester</option>
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester->id }}" 
                                                    {{ old('semester_id', $course->semester_id) == $semester->id ? 'selected' : '' }}
                                                    {{ $semester->is_current ? 'data-current="true"' : '' }}>
                                                {{ $semester->semester_name }}
                                                @if($semester->is_current)
                                                    (Current)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('semester_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-lightbulb"></i> Quick Tips:</h6>
                                    <ul class="mb-0">
                                        <li>Course codes must be unique across the system</li>
                                        <li>Each course must be associated with a programme, level, and semester</li>
                                        <li>Changing these associations may affect student registrations</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('superadmin.courses.show', $course) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Course
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
    // Highlight current semester in dropdown
    $('#semester_id option[data-current="true"]').css({
        'background-color': '#d4edda',
        'font-weight': 'bold'
    });
});
</script>
@endpush