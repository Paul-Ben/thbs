@extends('student.layout')
@section('content')
    <div class="card py-0"> 
        <div class="d-flex align-items-center ps-4" style="height: 80px;">
            <div class="page-header mb-0">
                <ul class="breadcrumbs mb-0">
                    <li class="nav-home">
                        <a href="{{ route('student.dashboard') }}"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('student.dashboard') }}">Student</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Fee Payments</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Outstanding Fees -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Outstanding School Fees</div>
                        <div class="card-tools">
                            <span class="badge bg-warning">{{ count($outstandingFees) }} Outstanding</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($outstandingFees as $fee)
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{{ $fee->name }}</h6>
                                        <p class="text-muted mb-1">
                                            {{ $fee->schoolSession->session_name ?? 'Current Session' }} - {{ $fee->semester->semester_name ?? 'Current Semester' }}
                                        </p>
                                        <p class="text-muted mb-1">
                                            {{ $fee->programme->name ?? 'Unknown Programme' }} - {{ $fee->level->name ?? 'Unknown Level' }}
                                        </p>
                                        <small class="text-muted">Due Date: {{ $fee->due_date ? $fee->due_date->format('M d, Y') : 'Not Set' }}</small>
                                        <br>
                                        <small class="text-info">Fee Type: {{ ucfirst($fee->fee_type) }}</small>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <h5 class="mb-0 text-primary">₦{{ number_format($fee->remaining_amount, 2) }}</h5>
                                        <small class="text-muted">Amount Due</small>
                                        @if($fee->remaining_amount < $fee->amount)
                                            <br>
                                            <small class="text-success">Paid: ₦{{ number_format($fee->amount - $fee->remaining_amount, 2) }}</small>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <form method="POST" action="{{ route('payment.school-fee.initialize') }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="school_fee_id" value="{{ $fee->id }}">
                                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                                            <input type="hidden" name="phone" value="{{ Auth::user()->phone ?? $student->phone ?? '' }}">
                                            <input type="hidden" name="matric_number" value="{{ $student->matric_number ?? '' }}">
                                            <input type="hidden" name="student_id" value="{{ $student->id ?? '' }}">
                                            <input type="hidden" name="programme_id" value="{{ $fee->programme_id ?? '' }}">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-credit-card me-1"></i> Pay Now
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                            <h4 class="text-success">All Fees Paid!</h4>
                            <p class="text-muted">You have no outstanding school fees at this time.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Payment History -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Recent Payment History</div>
                        <div class="card-tools">
                            <a href="{{ route('student.payments.history') }}" class="btn btn-link btn-sm">
                                View All Payments
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Session</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td>{{ $payment->schoolFee->session->session_name ?? 'N/A' }}</td>
                                        <td>₦{{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($payment->payment_method ?? 'Card') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $payment->status == 'successful' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment->status == 'successful')
                                                <a href="{{ route('student.payments.receipt', $payment->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-receipt"></i> Receipt
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No payment history available</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Payment Summary -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Payment Summary</div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h6 class="text-muted mb-1">Total Outstanding</h6>
                            <h4 class="text-danger mb-0">₦{{ number_format($totalOutstanding, 2) }}</h4>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">Total Paid</h6>
                            <h4 class="text-success mb-0">₦{{ number_format($totalPaid, 2) }}</h4>
                        </div>
                    </div>
                    
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $paymentProgress }}%" 
                             aria-valuenow="{{ $paymentProgress }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            {{ $paymentProgress }}%
                        </div>
                    </div>
                    
                    <small class="text-muted">Payment completion progress</small>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Available Payment Methods</div>
                </div>
                <div class="card-body">
                    <div class="payment-method mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <div class="avatar-title bg-primary rounded">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">Online Payment</h6>
                                <small class="text-muted">Pay with debit/credit card</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="payment-method mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <div class="avatar-title bg-success rounded">
                                    <i class="fas fa-university"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">Bank Transfer</h6>
                                <small class="text-muted">Direct bank transfer</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="payment-method mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <div class="avatar-title bg-info rounded">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">Mobile Money</h6>
                                <small class="text-muted">Pay with mobile wallet</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help & Support -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Need Help?</div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Having trouble with payments? Contact our support team.</p>
                    
                    <div class="mb-2">
                        <i class="fas fa-phone me-2 text-primary"></i>
                        <span>{{ $supportPhone ?? '+234-XXX-XXX-XXXX' }}</span>
                    </div>
                    
                    <div class="mb-2">
                        <i class="fas fa-envelope me-2 text-primary"></i>
                        <span>{{ $supportEmail ?? 'support@school.edu' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <i class="fas fa-clock me-2 text-primary"></i>
                        <span>Mon - Fri, 8AM - 5PM</span>
                    </div>
                    
                    <a href="{{ route('student.support') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-headset me-1"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>


@endsection