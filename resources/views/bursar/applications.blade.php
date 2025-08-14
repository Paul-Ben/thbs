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
                    <li class="nav-item"><a href="#">Payments</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Application Fees</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Application Fee Payments</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Export to Excel</a>
                                    <a class="dropdown-item" href="#">Export to PDF</a>
                                    <a class="dropdown-item" href="#">Print Report</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <!-- Payments table -->
                        <table id="paymentsTable" class="display table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">S/N</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Reference</th>
                                    
                                    <th scope="col" class="text-end">Amount</th>
                                    <th scope="col" class="text-end">Status</th>
                                    <th scope="col" class="text-end">Date</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $index => $payment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($payment->application)
                                            {{ $payment->application->applicant_surname }} {{ $payment->application->applicant_othernames }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->application)
                                            {{ $payment->application->email }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $payment->reference }}</td>
                                   
                                    <td class="text-end">
                                        <span class="fw-bold text-success">
                                            ₦{{ number_format($payment->amount, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge badge-success">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <small class="text-muted">{{ $payment->created_at->format('Y-m-d') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('bursar.payment.show', $payment->id) }}" class="btn btn-link btn-info btn-sm" title="View Payment Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Application Fee Payments</h5>
                                            <p class="text-muted">No successful application fee payments have been made yet.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Showing {{ $payments->count() }} application fee payments</small>
                        <div class="d-flex align-items-center">
                            <span class="me-3">
                                <strong>Total Revenue: ₦{{ number_format($payments->sum('amount'), 2) }}</strong>
                            </span>
                            <a href="{{ route('bursar.dashboard') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#paymentsTable").DataTable({
                "pageLength": 25,
                "order": [[ 7, "desc" ]], // Sort by date column (index 7) descending
                "columnDefs": [
                    { "orderable": false, "targets": 8 } // Disable sorting on Actions column
                ],
                "language": {
                    "search": "Search application fees:",
                    "lengthMenu": "Show _MENU_ application fee payments per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ application fee payments",
                    "infoEmpty": "No application fee payments available",
                    "infoFiltered": "(filtered from _MAX_ total application fee payments)"
                }
            });
        });
    </script>
@endsection