@extends('bursar.layout')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">School Fee Details</h3>
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
                    <a href="#">Fee Details</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="card-title">School Fee Information</div>
                            <div class="ms-auto">
                                <a href="{{ route('bursar.school-fees.edit', $schoolFee) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i> Edit Fee
                                </a>
                                <form action="{{ route('bursar.school-fees.destroy', $schoolFee) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this school fee? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Delete Fee
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Fee ID</label>
                                    <p class="form-control-static">#{{ $schoolFee->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Fee Name</label>
                                    <p class="form-control-static">{{ $schoolFee->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Fee Amount</label>
                                    <p class="form-control-static">
                                        <span class="text-success fw-bold fs-4">{{ $schoolFee->currency }} {{ number_format($schoolFee->amount, 2) }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Currency</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-primary">{{ $schoolFee->currency }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Programme</label>
                                    <p class="form-control-static">{{ $schoolFee->programme->programme_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">College</label>
                                    <p class="form-control-static">{{ $schoolFee->programme->college->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Academic Session</label>
                                    <p class="form-control-static">{{ $schoolFee->schoolSession->session_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Semester</label>
                                    <p class="form-control-static">{{ $schoolFee->semester->semester_name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Level</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-info">{{ $schoolFee->level->level_name }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Fee Type</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-secondary">{{ ucfirst($schoolFee->fee_type) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Status</label>
                                    <p class="form-control-static">
                                        <span class="badge {{ $schoolFee->is_active ? 'badge-success' : 'badge-warning' }}">
                                            {{ $schoolFee->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Unique Configuration</label>
                                    <p class="form-control-static">
                                        <small class="text-muted">{{ $schoolFee->programme->programme_name }} | {{ $schoolFee->schoolSession->session_name }} | {{ $schoolFee->semester->semester_name }} | {{ $schoolFee->level->level_name }} | {{ ucfirst($schoolFee->fee_type) }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        @if($schoolFee->due_date)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Due Date</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-warning">{{ $schoolFee->due_date->format('F d, Y') }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        @endif
                        
                        @if($schoolFee->description)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw-bold">Description</label>
                                    <p class="form-control-static">{{ $schoolFee->description }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Created Date</label>
                                    <p class="form-control-static">{{ $schoolFee->created_at->format('F d, Y \\a\\t g:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Last Updated</label>
                                    <p class="form-control-static">{{ $schoolFee->updated_at->format('F d, Y \\a\\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-action">
                        <a href="{{ route('bursar.school-fees.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('bursar.school-fees.edit', $schoolFee) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Edit Fee
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <div class="card-title">Fee Summary</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6><i class="fa fa-info-circle text-info"></i> Configuration Details</h6>
                            <ul class="list-unstyled small">
                                <li><strong>Fee Name:</strong> {{ $schoolFee->name }}</li>
                                <li><strong>Programme:</strong> {{ $schoolFee->programme->programme_name }}</li>
                                <li><strong>College:</strong> {{ $schoolFee->programme->college->name }}</li>
                                <li><strong>Session:</strong> {{ $schoolFee->schoolSession->session_name }}</li>
                                <li><strong>Semester:</strong> {{ $schoolFee->semester->semester_name }}</li>
                                <li><strong>Level:</strong> {{ $schoolFee->level->level_name }}</li>
                                <li><strong>Fee Type:</strong> {{ ucfirst($schoolFee->fee_type) }}</li>
                                <li><strong>Amount:</strong> {{ $schoolFee->currency }} {{ number_format($schoolFee->amount, 2) }}</li>
                                @if($schoolFee->due_date)
                                    <li><strong>Due Date:</strong> {{ $schoolFee->due_date->format('F d, Y') }}</li>
                                @endif
                                <li><strong>Status:</strong> 
                                    <span class="badge {{ $schoolFee->is_active ? 'badge-success' : 'badge-warning' }}">
                                        {{ $schoolFee->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-clock text-primary"></i> Timeline</h6>
                            <ul class="list-unstyled small">
                                <li><strong>Created:</strong> {{ $schoolFee->created_at->format('M d, Y') }}</li>
                                <li><strong>Last Updated:</strong> {{ $schoolFee->updated_at->format('M d, Y') }}</li>
                                @if($schoolFee->created_at != $schoolFee->updated_at)
                                    <li><strong>Modified:</strong> {{ $schoolFee->updated_at->diffForHumans() }}</li>
                                @endif
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-graduation-cap text-success"></i> Fee Type Information</h6>
                            <p class="small">
                                @switch($schoolFee->fee_type)
                                    @case('tuition')
                                        Main academic fee for course instruction and educational services.
                                        @break
                                    @case('accommodation')
                                        Fee for hostel accommodation and housing services.
                                        @break
                                    @case('library')
                                        Fee for library access, resources, and services.
                                        @break
                                    @case('laboratory')
                                        Fee for laboratory equipment, materials, and usage.
                                        @break
                                    @case('sports')
                                        Fee for sports facilities, equipment, and activities.
                                        @break
                                    @case('medical')
                                        Fee for health services and medical facilities.
                                        @break
                                    @case('development')
                                        Fee for infrastructure development and improvement.
                                        @break
                                    @case('examination')
                                        Fee for examination processing and administration.
                                        @break
                                    @case('registration')
                                        Fee for course registration and administrative services.
                                        @break
                                    @case('technology')
                                        Fee for IT services, equipment, and technology access.
                                        @break
                                    @case('miscellaneous')
                                        Other fees not covered by specific categories.
                                        @break
                                    @default
                                        General school fee for educational services.
                                @endswitch
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-exclamation-triangle text-warning"></i> Actions</h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('bursar.school-fees.edit', $schoolFee) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i> Edit This Fee
                                </a>
                                <a href="{{ route('bursar.school-fees.create') }}" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i> Add New Fee
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection