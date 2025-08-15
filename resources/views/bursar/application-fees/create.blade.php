@extends('bursar.layout')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Add Application Fee</h3>
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
                    <a href="#">Add Fee</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Application Fee Information</div>
                    </div>
                    <form action="{{ route('bursar.application-fees.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="programme_id">Programme <span class="text-danger">*</span></label>
                                        <select class="form-select @error('programme_id') is-invalid @enderror" id="programme_id" name="programme_id" required>
                                            <option value="">Select Programme</option>
                                            @foreach($programmes as $programme)
                                                <option value="{{ $programme->id }}" {{ old('programme_id') == $programme->id ? 'selected' : '' }}>
                                                    {{ $programme->name }} ({{ $programme->level }}) - {{ $programme->college->name }}
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
                                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0" max="999999.99" placeholder="0.00" required>
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
                                <i class="fa fa-save"></i> Create Application Fee
                            </button>
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
                        <div class="card-title">Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6><i class="fa fa-info-circle text-info"></i> Application Fee Guidelines</h6>
                            <ul class="list-unstyled small">
                                <li>• Each programme can have only one application fee</li>
                                <li>• Fees are charged when students apply for admission</li>
                                <li>• Amount should be reasonable and competitive</li>
                                <li>• Consider programme level and college when setting fees</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fa fa-exclamation-triangle text-warning"></i> Important Notes</h6>
                            <ul class="list-unstyled small">
                                <li>• Fees cannot be negative</li>
                                <li>• Maximum fee amount is ₦999,999.99</li>
                                <li>• Once set, fees can be updated anytime</li>
                                <li>• Deleting a fee will affect future applications</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection