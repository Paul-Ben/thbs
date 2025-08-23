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
                    <li class="nav-item"><a href="{{ route('student.payments.history') }}">Payment History</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Payment Receipt</a></li>
                </ul>
            </div>
        </div>
    </div>

    @if(isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif($payment)
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-round">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>Payment Receipt</h4>
                    </div>
                    <div class="card-body" id="receipt-content">
                        <!-- Institution Header -->
                        <div class="text-center mb-4">
                            <h3 class="text-primary mb-1">Institute of Health & Tech. BSUTH</h3>
                            <p class="text-muted mb-0">Bursar's Office</p>
                            <hr class="my-3">
                        </div>

                        <!-- Receipt Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Student Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $student->user->first_name }} {{ $student->user->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Matric No:</strong></td>
                                        <td>{{ $student->matric_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Programme:</strong></td>
                                        <td>{{ $student->programme->programme_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Level:</strong></td>
                                        <td>{{ $student->level->level_name ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Payment Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Receipt No:</strong></td>
                                        <td>{{ $payment->reference }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Transaction ID:</strong></td>
                                        <td>{{ $payment->transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Date:</strong></td>
                                        <td>{{ $payment->payment_date->format('F j, Y g:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Method:</strong></td>
                                        <td>{{ ucfirst($payment->payment_method ?? 'Card') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Fee Details -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Fee Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Description</th>
                                            <th>Session</th>
                                            <th>Semester</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $payment->schoolFee->fee_name ?? 'School Fee' }}</td>
                                            <td>{{ $payment->schoolFee->schoolSession->session_name ?? 'N/A' }}</td>
                                            <td>{{ $payment->schoolFee->semester->semester_name ?? 'N/A' }}</td>
                                            <td class="text-end">₦{{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="3" class="text-end">Total Amount Paid:</th>
                                            <th class="text-end">₦{{ number_format($payment->amount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="mb-4">
                            <div class="alert alert-{{ $payment->status == 'successful' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}" role="alert">
                                <i class="fas fa-{{ $payment->status == 'successful' ? 'check-circle' : ($payment->status == 'pending' ? 'clock' : 'times-circle') }} me-2"></i>
                                <strong>Payment Status:</strong> {{ ucfirst($payment->status) }}
                                @if($payment->status == 'successful')
                                    - This payment has been successfully processed and recorded.
                                @elseif($payment->status == 'pending')
                                    - This payment is currently being processed.
                                @else
                                    - This payment was not successful. Please contact the Bursar's Office.
                                @endif
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <p class="text-muted mb-2">This is an official receipt for payment made to Institute of Health & Tech. BSUTH</p>
                            <p class="text-muted mb-0"><small>Generated on {{ now()->format('F j, Y g:i A') }}</small></p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="card-footer text-center">
                        <button onclick="window.print()" class="btn btn-primary me-2">
                            <i class="fas fa-print me-1"></i> Print Receipt
                        </button>
                        <a href="{{ route('student.payments.history') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Payment History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Payment Not Found!</strong> The requested payment receipt could not be found.
        </div>
    @endif
@endsection

@push('styles')
<style>
    @media print {
        .card-header, .card-footer, .breadcrumbs, .btn {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        body {
            background: white !important;
        }
    }
</style>
@endpush