@extends('bursar.layout')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Application Fee</h3>
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
                    <a href="#">Edit Fee</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Application Fee Information</div>
                    </div>
                    <form action="{{ route('bursar.application-fees.update', $applicationFee) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="programme_id">Programme <span class="text-danger">*</span></label>
                                        <select class="form-select @error('programme_id') is-invalid @enderror" id="programme_id" name="programme_id" required>
                                            <option value="">Select Programme</option>
                                            @foreach($programmes as $programme)
                                                <option value="{{ $programme->id }}" 
                                                    {{ (old('programme_id', $applicationFee->programme_id) == $programme->id) ? 'selected' : '' }}>
                                                    {{ $programme->name }} ({{ $programme->level }}) - {{ $programme->department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('programme_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Select the programme for which you want to set the application fee.</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="amount">Application Fee Amount (₦) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₦</span>
                                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $applicationFee->amount) }}" step="0.01" min="0" max="999999.99" placeholder="0.00" required>
                                        </div>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Enter the application fee amount in Naira (₦). Maximum amount is ₦999,999.99</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update Application Fee
                            </button>
                            <a href="{{ route('bursar.application-fees.show', $applicationFee) }}" class="btn btn-info">
                                <i class="fa fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('bursar.application-fees.index') }}" class="btn btn-danger">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <div class="card-title">Current Fee Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6><i class="fa fa-graduation-cap text-primary"></i> Programme Details</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $applicationFee->programme->name }}</p>
                            <p class="mb-1"><strong>Level:</strong> <span class="badge badge-info">{{ $applicationFee->programme->level }}</span></p>
                            <p class="mb-1"><strong>Department:</strong> {{ $applicationFee->programme->department->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-money-bill-wave text-success"></i> Current Fee</h6>
                            <p class="mb-1"><strong>Amount:</strong> <span class="text-success fw-bold">₦{{ number_format($applicationFee->amount, 2) }}</span></p>
                            <p class="mb-1"><strong>Created:</strong> {{ $applicationFee->created_at->format('M d, Y') }}</p>
                            {{-- <p class="mb-1"><strong>Last Updated:</strong> {{ $applicationFee->updated_at->format('M d, Y') ?? " "}}</p> --}}
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-exclamation-triangle text-warning"></i> Update Guidelines</h6>
                            <ul class="list-unstyled small">
                                <li>• Ensure the new amount is competitive</li>
                                <li>• Consider impact on current applicants</li>
                                <li>• Changes take effect immediately</li>
                                <li>• Keep fees reasonable and justified</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection