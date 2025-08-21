@extends('bursar.layout')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit School Fee</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('bursar.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bursar.school-fees.index') }}">School Fees</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit Fee</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit School Fee Information</div>
                    </div>
                    <form action="{{ route('bursar.school-fees.update', $schoolFee) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="programme_id">Programme <span class="text-danger">*</span></label>
                                        <select class="form-select @error('programme_id') is-invalid @enderror" id="programme_id" name="programme_id" required>
                                            <option value="">Select Programme</option>
                                            @foreach($programmes as $programme)
                                                <option value="{{ $programme->id }}" 
                                                    {{ (old('programme_id', $schoolFee->programme_id) == $programme->id) ? 'selected' : '' }}>
                                                    {{ $programme->name }} - {{ $programme->college->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('programme_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_session_id">Academic Session <span class="text-danger">*</span></label>
                                        <select class="form-select @error('school_session_id') is-invalid @enderror" id="school_session_id" name="school_session_id" required>
                                            <option value="">Select Session</option>
                                            @foreach($sessions as $session)
                                                <option value="{{ $session->id }}" 
                                                    {{ (old('school_session_id', $schoolFee->school_session_id) == $session->id) ? 'selected' : '' }}>
                                                    {{ $session->session_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('school_session_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="semester_id">Semester <span class="text-danger">*</span></label>
                                        <select class="form-select @error('semester_id') is-invalid @enderror" id="semester_id" name="semester_id" required>
                                            <option value="">Select Semester</option>
                                            @foreach($semesters as $semester)
                                                <option value="{{ $semester->id }}" 
                                                    {{ (old('semester_id', $schoolFee->semester_id) == $semester->id) ? 'selected' : '' }}>
                                                    {{ $semester->semester_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('semester_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="level_id">Level <span class="text-danger">*</span></label>
                                        <select class="form-select @error('level_id') is-invalid @enderror" id="level_id" name="level_id" required>
                                            <option value="">Select Level</option>
                                            @foreach($levels as $level)
                                                <option value="{{ $level->id }}" 
                                                    {{ (old('level_id', $schoolFee->level_id) == $level->id) ? 'selected' : '' }}>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('level_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fee_type">Fee Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('fee_type') is-invalid @enderror" id="fee_type" name="fee_type" required>
                                            <option value="">Select Fee Type</option>
                                            @foreach($feeTypes as $value => $label)
                                                <option value="{{ $value }}" 
                                                    {{ (old('fee_type', $schoolFee->fee_type) == $value) ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('fee_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount">Fee Amount (₦) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₦</span>
                                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $schoolFee->amount) }}" step="0.01" min="0" max="9999999.99" placeholder="0.00" required>
                                        </div>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Fee Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $schoolFee->name) }}" placeholder="Enter fee name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="currency">Currency <span class="text-danger">*</span></label>
                                        <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                            <option value="NGN" {{ (old('currency', $schoolFee->currency) == 'NGN') ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                                            <option value="USD" {{ (old('currency', $schoolFee->currency) == 'USD') ? 'selected' : '' }}>USD - US Dollar</option>
                                            <option value="EUR" {{ (old('currency', $schoolFee->currency) == 'EUR') ? 'selected' : '' }}>EUR - Euro</option>
                                            <option value="GBP" {{ (old('currency', $schoolFee->currency) == 'GBP') ? 'selected' : '' }}>GBP - British Pound</option>
                                        </select>
                                        @error('currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="due_date">Due Date (Optional)</label>
                                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $schoolFee->due_date ? $schoolFee->due_date->format('Y-m-d') : '') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Leave blank if no specific due date.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $schoolFee->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active (Fee will be available for payment)
                                            </label>
                                        </div>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description (Optional)</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Enter fee description">{{ old('description', $schoolFee->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Additional information about this fee.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update School Fee
                            </button>
                            <a href="{{ route('bursar.school-fees.index') }}" class="btn btn-danger">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <div class="card-title">Current Fee Details</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6><i class="fa fa-info-circle text-info"></i> Current Configuration</h6>
                            <ul class="list-unstyled small">
                                <li><strong>Programme:</strong> {{ $schoolFee->programme->name }}</li>
                                <li><strong>Session:</strong> {{ $schoolFee->schoolSession->session_name }}</li>
                                <li><strong>Semester:</strong> {{ $schoolFee->semester->semester_name }}</li>
                                <li><strong>Level:</strong> {{ $schoolFee->level->name }}</li>
                                <li><strong>Fee Type:</strong> {{ ucfirst($schoolFee->fee_type) }}</li>
                                <li><strong>Amount:</strong> {{ $schoolFee->currency }} {{ number_format($schoolFee->amount, 2) }}</li>
                                <li><strong>Status:</strong> 
                                    <span class="badge {{ $schoolFee->is_active ? 'badge-success' : 'badge-warning' }}">
                                        {{ $schoolFee->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </li>
                                <li><strong>Created:</strong> {{ $schoolFee->created_at->format('M d, Y') }}</li>
                                <li><strong>Last Updated:</strong> {{ $schoolFee->updated_at->format('M d, Y') }}</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-exclamation-triangle text-warning"></i> Important Notes</h6>
                            <ul class="list-unstyled small">
                                <li>• Changing key fields may affect existing student records</li>
                                <li>• Ensure the new configuration doesn't duplicate existing fees</li>
                                <li>• Status changes take effect immediately</li>
                                <li>• Amount changes will apply to future transactions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection