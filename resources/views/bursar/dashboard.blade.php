@extends('bursar.layout')
@section('content')
    <div class="card py-0"> 
        <div class="d-flex align-items-center ps-4" style="height: 80px;">
            <div class="page-header mb-0">
                <ul class="breadcrumbs mb-0">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Finance</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Revenue</p>
                                <h4 class="card-title">₦{{ number_format($totalRevenue, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Application Revenue</p>
                                <h4 class="card-title">₦{{ number_format($applicationRevenue, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-university"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">School Charge Revenue</p>
                                <h4 class="card-title">₦{{ number_format($schoolChargeRevenue, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Recent Transactions</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Export to Excel</a>
                                    <a class="dropdown-item" href="#">Export to PDF</a>
                                    <a class="dropdown-item" href="{{ route('bursar.payments.application') }}">View All Application Fees</a>
                                    <a class="dropdown-item" href="{{ route('bursar.payments.school') }}">View All School Fees</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <!-- Transactions table -->
                        <table class="table align-items-center mb-0">
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
                                @forelse($recentTransactions as $index => $transaction)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($transaction->paymentable)
                                            @if($transaction->paymentable_type === 'App\Models\SchoolFeePayment')
                                                {{ $transaction->paymentable->student->surname ?? 'N/A' }} {{ $transaction->paymentable->student->othernames ?? '' }}
                                            @elseif($transaction->paymentable->application)
                                                {{ $transaction->paymentable->application->applicant_surname }} {{ $transaction->paymentable->application->applicant_othernames }}
                                            @else
                                                N/A
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->paymentable)
                                            @if($transaction->paymentable_type === 'App\Models\SchoolFeePayment')
                                                {{ $transaction->paymentable->student->email ?? 'N/A' }}
                                            @elseif($transaction->paymentable->application)
                                                {{ $transaction->paymentable->application->email }}
                                            @else
                                                N/A
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $transaction->paymentable->reference ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <span class="fw-bold text-{{ $transaction->status === 'successful' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                            ₦{{ number_format($transaction->amount, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge badge-{{ $transaction->status === 'successful' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <small class="text-muted">{{ $transaction->created_at->format('Y-m-d') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('bursar.transaction.show', $transaction->id) }}" class="btn btn-link btn-info btn-sm" title="View Transaction Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Recent Transactions</h5>
                                            <p class="text-muted">No payment transactions have been made yet.</p>
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
                        <small class="text-muted">Showing {{ $recentTransactions->count() }} of {{ $totalTransactions }} transactions</small>
                        @if($totalTransactions > 5)
                        <a href="#" class="btn btn-primary btn-sm">View All Transactions</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection