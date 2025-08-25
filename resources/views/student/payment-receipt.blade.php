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
                            <div class="row align-items-center mb-3">
                                <div class="col-md-3">
                                    <!-- Institution Logo -->
                                    <img src="{{ asset('assets/img/logo.svg') }}" alt="Institution Logo" class="img-fluid" style="max-height: 80px;" onerror="this.style.display='none'">
                                    <!-- Fallback to PNG if SVG fails -->
                                    <img src="{{ asset('assets/img/logo.png') }}" alt="Institution Logo" class="img-fluid" style="max-height: 80px; display: none;" onerror="this.style.display='none'" onload="this.previousElementSibling.style.display='none'; this.style.display='block';">
                                </div>
                                <div class="col-md-6">
                                    <h3 class="text-primary mb-1">Institute of Health & Tech. BSUTH</h3>
                                    <p class="text-muted mb-0">Bursar's Office</p>
                                </div>
                                <div class="col-md-3">
                                    <!-- QR Code with Student Information -->
                                    <div class="text-center">
                                        <div id="qr-code"></div>
                                        <small class="text-muted">Scan for Student Info</small>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3">
                        </div>

                        <!-- Receipt Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Student Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $student->applicant_name }} {{ $student->user->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Matric No:</strong></td>
                                        <td>{{ $student->matric_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Programme:</strong></td>
                                        <td>{{ $student->programme->name ?? 'N/A' }} {{ $student->programme->department->name}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Level:</strong></td>
                                        <td>{{ $student->level->name ?? 'N/A' }}</td>
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
                        <button onclick="downloadReceipt()" class="btn btn-success me-2">
                            <i class="fas fa-download me-1"></i> Download as Image
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>
<script>
    // Generate QR Code with student information
    document.addEventListener('DOMContentLoaded', function() {
        const studentInfo = {
            name: '{{ $student->user->first_name }} {{ $student->user->last_name }}',
            matricNumber: '{{ $student->matric_number }}',
            programme: '{{ $student->programme->programme_name ?? "N/A" }}',
            level: '{{ $student->level->level_name ?? "N/A" }}',
            department: '{{ $student->programme->department->department_name ?? "N/A" }}',
            session: '{{ $payment->schoolFee->schoolSession->session_name ?? "N/A" }}',
            paymentReference: '{{ $payment->reference }}',
            paymentDate: '{{ $payment->payment_date->format("Y-m-d") }}',
            amount: '{{ $payment->amount }}'
        };
        
        const qrData = JSON.stringify(studentInfo);
        
        QRCode.toCanvas(document.getElementById('qr-code'), qrData, {
            width: 100,
            height: 100,
            margin: 1,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        }, function (error) {
            if (error) {
                console.error('QR Code generation failed:', error);
                document.getElementById('qr-code').innerHTML = '<small class="text-muted">QR Code unavailable</small>';
            }
        });
    });
    function downloadReceipt() {
        const receiptElement = document.getElementById('receipt-content');
        const downloadBtn = document.querySelector('button[onclick="downloadReceipt()"]');
        
        // Show loading state
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Generating...';
        downloadBtn.disabled = true;
        
        // Configure html2canvas options
        const options = {
            scale: 2, // Higher resolution
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            width: receiptElement.scrollWidth,
            height: receiptElement.scrollHeight,
            scrollX: 0,
            scrollY: 0
        };
        
        html2canvas(receiptElement, options).then(function(canvas) {
            // Create download link
            const link = document.createElement('a');
            link.download = 'payment-receipt-{{ $payment->reference ?? "receipt" }}.png';
            link.href = canvas.toDataURL('image/png');
            
            // Trigger download
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Reset button state
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
        }).catch(function(error) {
            console.error('Error generating receipt image:', error);
            alert('Failed to generate receipt image. Please try again.');
            
            // Reset button state
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
        });
    }
</script>
@endpush

@push('styles')
<style>
    @media print {
        /* Hide all page elements except receipt content */
        body * {
            visibility: hidden;
        }
        
        /* Show only the receipt content and its children */
        #receipt-content, #receipt-content * {
            visibility: visible;
        }
        
        /* Position the receipt content at the top of the page */
        #receipt-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 20px;
        }
        
        /* Hide navigation, buttons, and other UI elements */
        .breadcrumbs, .btn, .card-header, .card-footer, 
        .navbar, .sidebar, .main-header, .main-sidebar,
        .content-wrapper, nav, header, footer {
            display: none !important;
            visibility: hidden !important;
        }
        
        /* Reset page styling for print */
        body {
            background: white !important;
            margin: 0;
            padding: 0;
            font-size: 12pt;
            line-height: 1.4;
        }
        
        /* Ensure proper page breaks */
        .page-break {
            page-break-before: always;
        }
        
        /* Style tables for print */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        table, th, td {
            border: 1px solid #000;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
        }
        
        /* Ensure text is black for print */
        * {
            color: black !important;
        }
        
        /* Ensure logo and QR code are visible in print */
        img, canvas {
            display: block !important;
            visibility: visible !important;
        }
        
        /* Style QR code for print */
        #qr-code canvas {
            border: 1px solid #ccc;
        }
    }
</style>
@endpush