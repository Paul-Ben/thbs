@extends('bursar.layout')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Add School Fee</h3>
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
                    <a href="#">Add Fee</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">School Fee Information</div>
                    </div>
                    <form action="{{ route('bursar.school-fees.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="programme_id">Programme <span class="text-danger">*</span></label>
                                        <select class="form-select @error('programme_id') is-invalid @enderror" id="programme_id" name="programme_id" required>
                                            <option value="">Select Programme</option>
                                            @foreach($programmes as $programme)
                                                <option value="{{ $programme->id }}" {{ old('programme_id') == $programme->id ? 'selected' : '' }}>
                                                    {{ $programme->programme_name }} - {{ $programme->college->name }}
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
                                                <option value="{{ $session->id }}" {{ old('school_session_id') == $session->id ? 'selected' : '' }}>
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
                                                <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
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
                                                <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                                    {{ $level->level_name }}
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
                                                <option value="{{ $value }}" {{ old('fee_type') == $value ? 'selected' : '' }}>
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
                                        <label for="amount">Fee Amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0" max="99999999.99" placeholder="0.00" required>
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
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter fee name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="currency">Currency <span class="text-danger">*</span></label>
                                        <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                            <option value="NGN" {{ old('currency', 'NGN') == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
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
                                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
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
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Enter fee description">{{ old('description') }}</textarea>
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
                                <i class="fa fa-save"></i> Create School Fee
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
                        <div class="card-title">Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6><i class="fa fa-info-circle text-info"></i> School Fee Guidelines</h6>
                            <ul class="list-unstyled small">
                                <li>• Each combination of programme, session, semester, level, and fee type must be unique</li>
                                <li>• Fees are charged based on student's programme and level</li>
                                <li>• Different fee types can have different amounts</li>
                                <li>• Consider programme requirements when setting fees</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-graduation-cap text-primary"></i> Fee Types</h6>
                            <ul class="list-unstyled small">
                                <li>• <strong>Tuition:</strong> Main academic fee</li>
                                <li>• <strong>Accommodation:</strong> Hostel/housing fees</li>
                                <li>• <strong>Library:</strong> Library access and services</li>
                                <li>• <strong>Laboratory:</strong> Lab equipment and materials</li>
                                <li>• <strong>Sports:</strong> Sports facilities and activities</li>
                                <li>• <strong>Medical:</strong> Health services</li>
                                <li>• <strong>Development:</strong> Infrastructure development</li>
                                <li>• <strong>Examination:</strong> Exam processing fees</li>
                                <li>• <strong>Registration:</strong> Course registration fees</li>
                                <li>• <strong>Technology:</strong> IT services and equipment</li>
                                <li>• <strong>Miscellaneous:</strong> Other fees</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-exclamation-triangle text-warning"></i> Important Notes</h6>
                            <ul class="list-unstyled small">
                                <li>• Fees cannot be negative</li>
                                <li>• Maximum fee amount is ₦9,999,999.99</li>
                                <li>• Only active fees will be charged</li>
                                <li>• Duplicate fee configurations are not allowed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection