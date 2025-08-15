@extends('bursar.layout')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Application Fee Details</h3>
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
                    <a href="{{ route('bursar.application-fees.index') }}">Application Fees</a>
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
                            <div class="card-title">Application Fee Information</div>
                            <div class="ms-auto">
                                <a href="{{ route('bursar.application-fees.edit', $applicationFee) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i> Edit Fee
                                </a>
                                <form action="{{ route('bursar.application-fees.destroy', $applicationFee) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this application fee? This action cannot be undone.')">
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
                                    <p class="form-control-static">#{{ $applicationFee->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Application Fee Amount</label>
                                    <p class="form-control-static">
                                        <span class="text-success fw-bold fs-4">₦{{ number_format($applicationFee->amount, 2) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw-bold">Programme Name</label>
                                    <p class="form-control-static">{{ $applicationFee->programme->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Programme Level</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-info">{{ $applicationFee->programme->level }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">College</label>
                                    <p class="form-control-static">{{ $applicationFee->programme->college->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Created Date</label>
                                    <p class="form-control-static">{{ $applicationFee->created_at->format('F d, Y \\a\\t g:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Last Updated</label>
                                    <p class="form-control-static">{{ $applicationFee->updated_at->format('F d, Y \\a\\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-action">
                        <a href="{{ route('bursar.application-fees.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('bursar.application-fees.edit', $applicationFee) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Edit Fee
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <div class="card-title">Fee Statistics</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6><i class="fa fa-chart-line text-primary"></i> Quick Stats</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Fee Amount:</span>
                                <span class="fw-bold text-success">₦{{ number_format($applicationFee->amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Programme:</span>
                                <span class="fw-bold">{{ $applicationFee->programme->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Level:</span>
                                <span class="badge badge-info">{{ $applicationFee->programme->level }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-info-circle text-info"></i> Programme Information</h6>
                            <p class="small mb-1"><strong>College:</strong> {{ $applicationFee->programme->college->name }}</p>
                            <p class="small mb-1"><strong>Programme ID:</strong> #{{ $applicationFee->programme->id }}</p>
                            <p class="small mb-1"><strong>Fee Status:</strong> <span class="badge badge-success">Active</span></p>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-clock text-warning"></i> Timeline</h6>
                            <div class="timeline timeline-simple">
                                <div class="timeline-item">
                                    <div class="timeline-time">{{ $applicationFee->created_at->format('M d, Y') }}</div>
                                    <div class="timeline-content">
                                        <small>Fee created</small>
                                    </div>
                                </div>
                                @if($applicationFee->created_at != $applicationFee->updated_at)
                                <div class="timeline-item">
                                    <div class="timeline-time">{{ $applicationFee->updated_at->format('M d, Y') }}</div>
                                    <div class="timeline-content">
                                        <small>Last updated</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="card-title">Actions</div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('bursar.application-fees.edit', $applicationFee) }}" class="btn btn-primary">
                                <i class="fa fa-edit"></i> Edit This Fee
                            </a>
                            <a href="{{ route('bursar.application-fees.create') }}" class="btn btn-success">
                                <i class="fa fa-plus"></i> Add New Fee
                            </a>
                            <a href="{{ route('bursar.application-fees.index') }}" class="btn btn-secondary">
                                <i class="fa fa-list"></i> View All Fees
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection