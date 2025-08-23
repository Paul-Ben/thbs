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
                    <li class="nav-item"><a href="#">Payment History</a></li>
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
        <div class="col-md-12">
            <!-- Payment Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card card-round">
                        <div class="card-body text-center">
                            <h4 class="text-success mb-1">₦{{ number_format($totalPaid, 2) }}</h4>
                            <p class="text-muted mb-0">Total Paid</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-round">
                        <div class="card-body text-center">
                            <h4 class="text-primary mb-1">{{ $totalPayments }}</h4>
                            <p class="text-muted mb-0">Total Payments</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-round">
                        <div class="card-body text-center">
                            <h4 class="text-info mb-1">{{ $successfulPayments }}</h4>
                            <p class="text-muted mb-0">Successful</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-round">
                        <div class="card-body text-center">
                            <h4 class="text-warning mb-1">{{ $pendingPayments }}</h4>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card card-round mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('student.payments.history') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="session_filter" class="form-label">Academic Session</label>
                            <select class="form-select" id="session_filter" name="session">
                                <option value="">All Sessions</option>
                                @foreach($availableSessions as $session)
                                    <option value="{{ $session->id }}" {{ request('session') == $session->id ? 'selected' : '' }}>
                                        {{ $session->session_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label">Payment Status</label>
                            <select class="form-select" id="status_filter" name="status">
                                <option value="">All Status</option>
                                <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('student.payments.history') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment History Table -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Payment History</div>
                        <div class="card-tools">
                            <a href="{{ route('student.payments.fees') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Make Payment
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="paymentHistoryTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Session</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $payment->created_at->format('M d, Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $payment->reference }}</code>
                                        </td>
                                        <td>{{ $payment->schoolFee->schoolSession->session_name ?? 'N/A' }}</td>
                                        <td>
                                            <strong class="text-primary">₦{{ number_format($payment->amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucfirst($payment->payment_method ?? 'Card') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $payment->status == 'successful' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($payment->status == 'successful')
                                                    <a href="{{ route('student.payments.receipt', $payment->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       target="_blank"
                                                       data-bs-toggle="tooltip" 
                                                       title="Download Receipt">
                                                        <i class="fas fa-receipt"></i>
                                                    </a>
                                                @endif
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-info view-details-btn" 
                                                        data-payment-id="{{ $payment->id }}"
                                                        data-bs-toggle="tooltip" 
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="fas fa-history fa-4x text-muted mb-3"></i>
                                            <h4 class="text-muted">No Payment History</h4>
                                            <p class="text-muted mb-4">You haven't made any payments yet.</p>
                                            <a href="{{ route('student.payments.fees') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> Make Your First Payment
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($payments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentDetailsModalLabel">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="paymentDetailsContent">
                    <!-- Payment details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Initialize DataTable for better sorting and searching
        $('#paymentHistoryTable').DataTable({
            "pageLength": 25,
            "order": [[ 0, "desc" ]],
            "columnDefs": [
                { "orderable": false, "targets": [6] }
            ],
            "language": {
                "search": "Search payments:",
                "lengthMenu": "Show _MENU_ payments per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ payments",
                "infoEmpty": "No payments found",
                "infoFiltered": "(filtered from _MAX_ total payments)"
            }
        });
        
        // Handle view details button click
        $('.view-details-btn').on('click', function() {
            var paymentId = $(this).data('payment-id');
            
            // Show loading state
            $('#paymentDetailsContent').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Loading payment details...</p></div>');
            $('#paymentDetailsModal').modal('show');
            
            // Load payment details via AJAX
            $.get('/student/payments/' + paymentId + '/details')
                .done(function(data) {
                    $('#paymentDetailsContent').html(data);
                })
                .fail(function() {
                    $('#paymentDetailsContent').html('<div class="alert alert-danger">Failed to load payment details. Please try again.</div>');
                });
        });
        
        // Auto-submit form when filters change
        $('#session_filter, #status_filter').on('change', function() {
            $(this).closest('form').submit();
        });
    });
</script>
@endsection