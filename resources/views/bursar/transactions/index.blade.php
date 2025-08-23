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
                    <li class="nav-item"><a href="#">Transactions</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">All Transactions</div>
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
                        <!-- Transactions table -->
                        <table id="transactionsTable" class="display table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">S/N</th>
                                    <th scope="col">Payment Type</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $index => $transaction)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $transaction->type }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaction->paymentable)
                                                @if($transaction->paymentable_type === 'App\Models\SchoolFeePayment')
                                                    {{ $transaction->paymentable->student->applicant_name ?? 'N/A' }} {{ $transaction->paymentable->student->othernames ?? '' }}
                                                @elseif($transaction->paymentable->application)
                                                    {{ $transaction->paymentable->application->applicant_surname }} {{ $transaction->paymentable->application->applicant_othernames }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->paymentable)
                                                @if($transaction->paymentable_type === 'App\Models\SchoolFeePayment')
                                                    {{ $transaction->paymentable->student->email ?? 'N/A' }}
                                                @elseif($transaction->paymentable->application)
                                                    {{ $transaction->paymentable->application->email }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                ₦{{ number_format($transaction->amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $transaction->created_at->format('Y-m-d') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('bursar.transaction.show', $transaction->id) }}" class="btn btn-link btn-info btn-sm" title="View Transaction Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Transactions Found</h5>
                                            <p class="text-muted">No transaction records have been created yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Showing {{ $transactions->count() }} transactions</small>
                        </div>
                        <div>
                            <strong>Total Amount: ₦{{ number_format($transactions->sum('amount'), 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $("#transactionsTable").DataTable({
        "pageLength": 10,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "language": {
            "lengthMenu": "Show _MENU_ transactions per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ transactions",
            "infoEmpty": "No transactions available",
            "infoFiltered": "(filtered from _MAX_ total transactions)"
        }
    });
});
</script>
@endpush