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
                                        <h6 class="mb-1">{{ $fee->session->session_name ?? 'Current Session' }}</h6>
                                        <p class="text-muted mb-1">
                                            {{ $student->programme->name ?? 'Unknown Programme' }} - {{ $fee->level->level_name ?? 'Unknown Level' }}
                                        </p>
                                        <small class="text-muted">Due Date: {{ $fee->due_date ?? 'Not Set' }}</small>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <h5 class="mb-0 text-primary">₦{{ number_format($fee->amount, 2) }}</h5>
                                        <small class="text-muted">Amount Due</small>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <button type="button" class="btn btn-primary btn-sm pay-fee-btn" 
                                                data-fee-id="{{ $fee->id }}" 
                                                data-amount="{{ $fee->amount }}" 
                                                data-session="{{ $fee->session->session_name ?? 'Current Session' }}">
                                            <i class="fas fa-credit-card me-1"></i> Pay Now
                                        </button>
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

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm" method="POST" action="{{ route('student.payments.process') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="fee_id" name="fee_id">
                        
                        <!-- Payment Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Payment Details</h6>
                                <p class="mb-1"><strong>Session:</strong> <span id="payment_session"></span></p>
                                <p class="mb-1"><strong>Student:</strong> {{ Auth::user()->name }}</p>
                                <p class="mb-0"><strong>Matric Number:</strong> {{ Auth::user()->student->matric_number ?? 'Not Assigned' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Amount</h6>
                                <h3 class="text-primary mb-0">₦<span id="payment_amount"></span></h3>
                            </div>
                        </div>
                        
                        <!-- Payment Method Selection -->
                        <div class="form-group mb-4">
                            <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="card">Debit/Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                        </div>
                        
                        <!-- Card Payment Fields -->
                        <div id="cardPaymentFields" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="card_number">Card Number</label>
                                        <input type="text" class="form-control" id="card_number" name="card_number" 
                                               placeholder="1234 5678 9012 3456" maxlength="19">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expiry_date">Expiry Date</label>
                                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" 
                                               placeholder="MM/YY" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cvv">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" 
                                               placeholder="123" maxlength="4">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="card_name">Cardholder Name</label>
                                <input type="text" class="form-control" id="card_name" name="card_name" 
                                       placeholder="John Doe">
                            </div>
                        </div>
                        
                        <!-- Bank Transfer Fields -->
                        <div id="bankTransferFields" style="display: none;">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-1"></i> Bank Transfer Details:</h6>
                                <p class="mb-1"><strong>Bank:</strong> {{ $bankDetails['name'] ?? 'School Bank' }}</p>
                                <p class="mb-1"><strong>Account Number:</strong> {{ $bankDetails['account_number'] ?? 'XXXXXXXXXX' }}</p>
                                <p class="mb-1"><strong>Account Name:</strong> {{ $bankDetails['account_name'] ?? 'School Account' }}</p>
                                <p class="mb-0"><strong>Reference:</strong> Use your matric number as reference</p>
                            </div>
                            
                            <div class="form-group">
                                <label for="transfer_reference">Transfer Reference/Receipt Number</label>
                                <input type="text" class="form-control" id="transfer_reference" name="transfer_reference" 
                                       placeholder="Enter transfer reference number">
                                <small class="text-muted">Enter the reference number from your bank transfer receipt</small>
                            </div>
                        </div>
                        
                        <!-- Mobile Money Fields -->
                        <div id="mobileMoney" style="display: none;">
                            <div class="form-group">
                                <label for="mobile_provider">Mobile Money Provider</label>
                                <select class="form-control" id="mobile_provider" name="mobile_provider">
                                    <option value="">Select Provider</option>
                                    <option value="mtn">MTN Mobile Money</option>
                                    <option value="airtel">Airtel Money</option>
                                    <option value="glo">Glo Mobile Money</option>
                                    <option value="9mobile">9mobile Money</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobile_number" name="mobile_number" 
                                       placeholder="08012345678">
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                I agree to the <a href="#" target="_blank">terms and conditions</a> and confirm the payment details are correct.
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="processPaymentBtn">
                            <i class="fas fa-credit-card me-1"></i> Process Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Pay fee button click
    $('.pay-fee-btn').click(function() {
        const feeId = $(this).data('fee-id');
        const amount = $(this).data('amount');
        const session = $(this).data('session');
        
        $('#fee_id').val(feeId);
        $('#payment_amount').text(new Intl.NumberFormat().format(amount));
        $('#payment_session').text(session);
        
        $('#paymentModal').modal('show');
    });
    
    // Payment method change
    $('#payment_method').change(function() {
        const method = $(this).val();
        
        // Hide all payment fields
        $('#cardPaymentFields, #bankTransferFields, #mobileMoney').hide();
        
        // Show relevant fields
        if (method === 'card') {
            $('#cardPaymentFields').show();
        } else if (method === 'bank_transfer') {
            $('#bankTransferFields').show();
        } else if (method === 'mobile_money') {
            $('#mobileMoney').show();
        }
    });
    
    // Card number formatting
    $('#card_number').on('input', function() {
        let value = $(this).val().replace(/\s/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        $(this).val(formattedValue);
    });
    
    // Expiry date formatting
    $('#expiry_date').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        $(this).val(value);
    });
    
    // CVV validation
    $('#cvv').on('input', function() {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });
    
    // Form validation
    $('#paymentForm').submit(function(e) {
        const paymentMethod = $('#payment_method').val();
        
        if (!paymentMethod) {
            e.preventDefault();
            alert('Please select a payment method.');
            return;
        }
        
        if (paymentMethod === 'card') {
            const cardNumber = $('#card_number').val().replace(/\s/g, '');
            const expiryDate = $('#expiry_date').val();
            const cvv = $('#cvv').val();
            const cardName = $('#card_name').val();
            
            if (!cardNumber || cardNumber.length < 13 || !expiryDate || !cvv || !cardName) {
                e.preventDefault();
                alert('Please fill in all card details.');
                return;
            }
        }
        
        if (paymentMethod === 'bank_transfer') {
            const transferRef = $('#transfer_reference').val();
            if (!transferRef) {
                e.preventDefault();
                alert('Please enter the transfer reference number.');
                return;
            }
        }
        
        if (paymentMethod === 'mobile_money') {
            const provider = $('#mobile_provider').val();
            const mobileNumber = $('#mobile_number').val();
            if (!provider || !mobileNumber) {
                e.preventDefault();
                alert('Please fill in mobile money details.');
                return;
            }
        }
        
        if (!$('#agree_terms').is(':checked')) {
            e.preventDefault();
            alert('Please agree to the terms and conditions.');
            return;
        }
        
        // Show loading state
        $('#processPaymentBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
    });
});
</script>
@endpush