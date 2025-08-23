@extends('bursar.layout')
@section('content')
    <div class="card py-0"> 
        <div class="d-flex align-items-center ps-4" style="height: 80px;">
            <div class="page-header mb-0">
                <ul class="breadcrumbs mb-0">
                    <li class="nav-home">
                        <a href="{{ route('bursar.dashboard') }}"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('bursar.dashboard') }}">Dashboard</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('bursar.payments.school') }}">School Fee Payments</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Payment Details</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Payment Information Card -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">School Fee Payment Details</div>
                        <div class="card-tools">
                            <span class="badge badge-success badge-lg">{{ ucfirst($payment->status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Payment Reference</strong></label>
                                <p class="form-control-static">{{ $payment->reference }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Transaction ID</strong></label>
                                <p class="form-control-static">{{ $payment->transaction_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Payment Method</strong></label>
                                <p class="form-control-static">{{ ucfirst($payment->payment_method) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Amount Paid</strong></label>
                                <p class="form-control-static">
                                    <span class="fw-bold text-success fs-4">₦{{ number_format($payment->amount, 2) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Payment Date</strong></label>
                                <p class="form-control-static">{{ $payment->payment_date ? $payment->payment_date->format('F j, Y g:i A') : $payment->created_at->format('F j, Y g:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Currency</strong></label>
                                <p class="form-control-static">{{ strtoupper($payment->currency ?? 'NGN') }}</p>
                            </div>
                        </div>
                    </div>
                    @if($payment->description)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label"><strong>Description</strong></label>
                                <p class="form-control-static">{{ $payment->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Fee Information Card -->
            @if($payment->schoolFee)
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Fee Information</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Fee Name</strong></label>
                                <p class="form-control-static">{{ $payment->schoolFee->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Fee Type</strong></label>
                                <p class="form-control-static">{{ ucfirst($payment->schoolFee->fee_type ?? 'N/A') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Academic Session</strong></label>
                                <p class="form-control-static">{{ $payment->schoolFee->schoolSession->session_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><strong>Semester</strong></label>
                                <p class="form-control-static">{{ $payment->schoolFee->semester->semester_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label"><strong>Fee Description</strong></label>
                                <p class="form-control-static">{{ $payment->schoolFee->description ?? 'No description available' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Student Information Card -->
            @if($payment->student)
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Student Information</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label"><strong>Student ID</strong></label>
                        <p class="form-control-static">{{ $payment->student->student_id }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><strong>Full Name</strong></label>
                        <p class="form-control-static">{{ $payment->student->surname }} {{ $payment->student->othernames }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><strong>Email</strong></label>
                        <p class="form-control-static">{{ $payment->student->email }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><strong>Phone</strong></label>
                        <p class="form-control-static">{{ $payment->student->phone ?? 'N/A' }}</p>
                    </div>
                    @if($payment->student->programme)
                    <div class="form-group">
                        <label class="form-label"><strong>Programme</strong></label>
                        <p class="form-control-static">{{ $payment->student->programme->name }}</p>
                    </div>
                    @endif
                    @if($payment->student->level)
                    <div class="form-group">
                        <label class="form-label"><strong>Level</strong></label>
                        <p class="form-control-static">{{ $payment->student->level->name ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions Card -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Actions</div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('bursar.payments.school') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to School Fee Payments
                        </a>
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Payment Details
                        </button>
                        @if($payment->transactions->count() > 0)
                        <a href="{{ route('bursar.transaction.show', $payment->transactions->first()->id) }}" class="btn btn-info">
                            <i class="fas fa-receipt"></i> View Transaction
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Metadata -->
            @if($payment->metadata && is_array($payment->metadata))
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Additional Information</div>
                </div>
                <div class="card-body">
                    @if(isset($payment->metadata['fee_details']))
                        @foreach($payment->metadata['fee_details'] as $key => $value)
                            @if($value && !in_array($key, ['programme_id', 'level_id', 'school_session_id', 'semester_id']))
                            <div class="form-group">
                                <label class="form-label"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong></label>
                                <p class="form-control-static">{{ $value }}</p>
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection