@extends('bursar.layout')

@section('content')
<div class="container-fluid">
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
                    <li class="nav-item"><a href="{{ route('bursar.transactions') }}">Transactions</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Transaction Details</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">#{{ $transaction->id }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Transaction Information</h4>
                        <div class="ms-auto">
                            <a href="{{ route('bursar.transactions') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to Transactions
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-bold">Transaction Type</label>
                                <p class="form-control-static">
                                    <span class="badge badge-info">{{ $transaction->type }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Amount</label>
                                <p class="form-control-static">
                                    <span class="fw-bold text-success fs-4">₦{{ number_format($transaction->amount, 2) }}</span>
                                    <small class="text-muted d-block">{{ $transaction->currency_code }}</small>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Transaction Status</label>
                                <p class="form-control-static">
                                    <span class="badge badge-success badge-lg">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Reconciliation Status</label>
                                <p class="form-control-static">
                                    <span class="badge badge-{{ $transaction->is_reconciled ? 'success' : 'warning' }} badge-lg">
                                        {{ $transaction->is_reconciled ? 'Reconciled' : 'Not Reconciled' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Transaction Date</label>
                                <p class="form-control-static">
                                    {{ $transaction->created_at->format('F d, Y \a\t g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information Card -->
            @if($transaction->paymentable)
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Payment Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Payment Reference</label>
                                <div class="d-flex align-items-center">
                                    <p class="form-control-static mb-0 me-2" id="payment-reference">{{ $transaction->paymentable->reference }}</p>
                                    <button type="button" class="btn btn-sm text-muted" style="border: none; background: none; padding: 4px 8px;" onclick="copyToClipboard('payment-reference')" title="Copy Reference">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Payment Method</label>
                                <p class="form-control-static">{{ ucfirst($transaction->paymentable->payment_method ?? 'N/A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Payment Status</label>
                                <p class="form-control-static">
                                    <span class="badge badge-{{ $transaction->paymentable->status === 'successful' ? 'success' : ($transaction->paymentable->status === 'pending' ? 'warning' : 'danger') }} badge-lg">
                                        {{ ucfirst($transaction->paymentable->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Payment Date</label>
                                <p class="form-control-static">
                                    {{ $transaction->paymentable->payment_date ? $transaction->paymentable->payment_date->format('F d, Y \a\t g:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-bold">Description</label>
                                <p class="form-control-static">{{ $transaction->paymentable->description ?? 'No description available' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('bursar.payment.show', $transaction->paymentable->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> View Full Payment Details
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payer Information Card -->
            @if($transaction->paymentable)
                @if($transaction->paymentable_type === 'App\Models\SchoolFeePayment')
                    <!-- Student Information Card -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Student Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Student ID</label>
                                        <p class="form-control-static">{{ $transaction->paymentable->student->student_id ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Full Name</label>
                                        <p class="form-control-static">
                                            {{ $transaction->paymentable->student->surname ?? 'N/A' }} {{ $transaction->paymentable->student->othernames ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Email</label>
                                        <p class="form-control-static">{{ $transaction->paymentable->student->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Phone</label>
                                        <p class="form-control-static">{{ $transaction->paymentable->student->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">School Fee</label>
                                        <p class="form-control-static">
                                            {{ $transaction->paymentable->schoolFee->name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Academic Session</label>
                                        <p class="form-control-static">
                                            {{ $transaction->paymentable->schoolFee->academic_session ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($transaction->paymentable->application)
                    <!-- Applicant Information Card -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Applicant Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Application Number</label>
                                        <p class="form-control-static">{{ $transaction->paymentable->application->application_number ?? 'Not Generated' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Full Name</label>
                                        <p class="form-control-static">
                                            {{ $transaction->paymentable->application->applicant_surname }} {{ $transaction->paymentable->application->applicant_othernames }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Email</label>
                                        <p class="form-control-static">{{ $transaction->paymentable->application->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Phone</label>
                                        <p class="form-control-static">{{ $transaction->paymentable->application->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Programme</label>
                                        <p class="form-control-static">
                                            {{ $transaction->paymentable->application->programme ? $transaction->paymentable->application->programme->name : 'Not Assigned' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Application Status</label>
                                        <p class="form-control-static">
                                            <span class="badge badge-info">{{ ucfirst($transaction->paymentable->application->status ?? 'pending') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Right Sidebar -->
        <div class="col-md-4">
            <!-- Transaction Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Transaction Actions</h4>
                </div>
                <div class="card-body">
                    @if(!$transaction->is_reconciled)
                        <button type="button" class="btn btn-success btn-block" data-bs-toggle="modal" data-bs-target="#reconcileModal">
                            <i class="fa fa-check"></i> Reconcile Transaction
                        </button>
                        <small class="text-muted">Click to mark this transaction as reconciled</small>
                    @else
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> Transaction Reconciled
                        </div>
                        <small class="text-muted">This transaction has been reconciled</small>
                    @endif
                </div>
            </div>

            <!-- Transaction Statistics Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Transaction Statistics</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center">
                            <h3 class="text-primary">{{ $transaction->id }}</h3>
                            <small class="text-muted">Transaction ID</small>
                        </div>
                        <div class="col-6 text-center">
                            <h3 class="text-success">{{ $transaction->created_at->diffForHumans() }}</h3>
                            <small class="text-muted">Created</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3 class="text-info">₦{{ number_format($transaction->amount, 2) }}</h3>
                            <small class="text-muted">Transaction Amount</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Type Info Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Payment Type Information</h4>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-{{ $transaction->isApplicationFeePayment() ? 'graduation-cap' : 'university' }} fa-3x text-info mb-3"></i>
                        <h5>{{ $transaction->type }}</h5>
                        <p class="text-muted">
                            @if($transaction->isApplicationFeePayment())
                                This transaction is related to an application fee payment.
                            @elseif($transaction->isSchoolFeePayment())
                                This transaction is related to a school fee payment.
                            @else
                                This transaction is related to other payment types.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reconcile Transaction Modal -->
@if(!$transaction->is_reconciled)
<div class="modal fade" id="reconcileModal" tabindex="-1" aria-labelledby="reconcileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="reconcileModalLabel">
                    <i class="fa fa-check-circle me-2"></i>Reconcile Transaction
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fa fa-handshake fa-2x text-success"></i>
                    </div>
                </div>
                
                <h6 class="text-center mb-3">Transaction Details</h6>
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Transaction ID:</small>
                        <div class="fw-bold">#{{ $transaction->id }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Amount:</small>
                        <div class="fw-bold text-success">₦{{ number_format($transaction->amount, 2) }}</div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <small class="text-muted">Type:</small>
                        <div class="fw-bold">{{ $transaction->type }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Date:</small>
                        <div class="fw-bold">{{ $transaction->created_at->format('M d, Y') }}</div>
                    </div>
                </div>

                <div class="alert alert-warning d-flex align-items-center">
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong>Important:</strong> Once reconciled, this action cannot be undone. Please ensure all transaction details are correct before proceeding.
                    </div>
                </div>

                <p class="text-center text-muted mb-0">
                    Are you sure you want to mark this transaction as reconciled?
                </p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Cancel
                </button>
                <form action="{{ route('bursar.transaction.reconcile', $transaction) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check me-1"></i>Yes, Reconcile Transaction
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;
    
    navigator.clipboard.writeText(text).then(function() {
        showCopyMessage('Copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}

function showCopyMessage(message) {
    // Create a temporary toast message
    const toast = document.createElement('div');
    toast.className = 'alert alert-success position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 200px;';
    toast.innerHTML = '<i class="fa fa-check"></i> ' + message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 2000);
}
</script>
@endpush