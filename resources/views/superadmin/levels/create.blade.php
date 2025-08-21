@extends('layouts.dashboard')

@section('title', 'Create New Level')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Create New Level
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.levels.index') }}" class="btn btn-secondary btn-sm">
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

                    <form action="{{ route('superadmin.levels.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-layer-group"></i> Level Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="e.g., 100 Level, 200 Level"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> 
                                        Enter a descriptive name for the level (e.g., "100 Level", "200 Level")
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
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
                                                    {{ old('semester_id') == $semester->id ? 'selected' : '' }}
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
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> 
                                        Select the semester this level belongs to
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-lightbulb"></i> Quick Tips:</h6>
                                    <ul class="mb-0">
                                        <li>Level names should follow your institution's naming convention</li>
                                        <li>Each level must be associated with a semester</li>
                                        <li>Levels help organize courses by academic progression</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('superadmin.levels.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Level
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
    
    // Auto-select current semester if no selection made
    if (!$('#semester_id').val()) {
        $('#semester_id option[data-current="true"]').prop('selected', true);
    }
});
</script>
@endpush