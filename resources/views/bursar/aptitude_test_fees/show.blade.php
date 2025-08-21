@extends('bursar.layout')

@section('title', 'Aptitude Test Fee Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Aptitude Test Fee Details</h3>
                    <div>
                        <a href="{{ route('bursar.aptitude-test-fees.edit', $aptitudeTestFee) }}" class="btn btn-warning mr-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('bursar.aptitude-test-fees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Fee Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">
                                        <i class="fas fa-tag"></i> Fee Information
                                    </h5>
                                    <div class="row">
                                        <div class="col-12">
                                            <strong>Fee Name:</strong><br>
                                            <span class="h6">{{ $aptitudeTestFee->name }}</span>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <strong>Status:</strong><br>
                                            @if($aptitudeTestFee->is_active)
                                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Active</span>
                                            @else
                                                <span class="badge badge-secondary"><i class="fas fa-pause-circle"></i> Inactive</span>
                                            @endif
                                        </div>
                                        @if($aptitudeTestFee->description)
                                            <div class="col-12 mt-2">
                                                <strong>Description:</strong><br>
                                                <small class="text-muted">{{ $aptitudeTestFee->description }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <h5 class="card-title text-success">
                                        <i class="fas fa-money-bill-wave"></i> Amount Information
                                    </h5>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Amount:</strong><br>
                                            <span class="h4 text-success">{{ number_format($aptitudeTestFee->amount, 2) }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Currency:</strong><br>
                                            <span class="badge badge-success badge-lg">{{ strtoupper($aptitudeTestFee->currency) }}</span>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <strong>Applies To:</strong><br>
                                            <span class="text-info"><i class="fas fa-users"></i> All Programs</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <div class="h2">{{ $totalPayments }}</div>
                                    <div>Total Payments</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <div class="h2">{{ $successfulPayments }}</div>
                                    <div>Successful Payments</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <div class="h2">{{ $totalPayments - $successfulPayments }}</div>
                                    <div>Failed/Pending</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <div class="h2">{{ number_format($totalRevenue, 0) }}</div>
                                    <div>Total Revenue ({{ strtoupper($aptitudeTestFee->currency) }})</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Payments -->
                    @if(isset($aptitudeTestFee->aptitudeTestPayments) && $aptitudeTestFee->aptitudeTestPayments->count() > 0)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-credit-card"></i> Recent Payments
                                            <span class="badge badge-info ml-2">{{ $totalPayments }}</span>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Student</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Reference</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($aptitudeTestFee->aptitudeTestPayments as $payment)
                                                        <tr>
                                                            <td>
                                                                <small>{{ $payment->created_at->format('M d, Y H:i') }}</small>
                                                            </td>
                                                            <td>
                                                                @if($payment->application && $payment->application->user)
                                                    {{ $payment->application->user->first_name }} {{ $payment->application->user->last_name }}
                                                    <br><small class="text-muted">{{ $payment->application->user->email }}</small>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                                            </td>
                                                            <td>
                                                                <strong>{{ number_format($payment->amount, 2) }}</strong>
                                                                <small class="text-muted">{{ strtoupper($payment->currency ?? $aptitudeTestFee->currency) }}</small>
                                                            </td>
                                                            <td>
                                                                @switch($payment->status)
                                                                    @case('successful')
                                                                        <span class="badge badge-success">Successful</span>
                                                                        @break
                                                                    @case('failed')
                                                                        <span class="badge badge-danger">Failed</span>
                                                                        @break
                                                                    @case('pending')
                                                                        <span class="badge badge-warning">Pending</span>
                                                                        @break
                                                                    @default
                                                                        <span class="badge badge-secondary">{{ ucfirst($payment->status) }}</span>
                                                                @endswitch
                                                            </td>
                                                            <td>
                                                                @if($payment->reference)
                                                                    <code>{{ $payment->reference }}</code>
                                                                @else
                                                                    <span class="text-muted">N/A</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($totalPayments > 10)
                                            <div class="text-center mt-3">
                                                <small class="text-muted">
                                                    Showing 10 of {{ $totalPayments }} payments
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Metadata -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0"><i class="fas fa-clock"></i> Metadata</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Created:</strong><br>
                                            <span class="text-muted">{{ $aptitudeTestFee->created_at->format('F d, Y \a\t H:i') }}</span>
                                            <br><small class="text-muted">{{ $aptitudeTestFee->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Last Updated:</strong><br>
                                            <span class="text-muted">{{ $aptitudeTestFee->updated_at->format('F d, Y \a\t H:i') }}</span>
                                            <br><small class="text-muted">{{ $aptitudeTestFee->updated_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Fee ID:</strong><br>
                                            <code>{{ $aptitudeTestFee->id }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <a href="{{ route('bursar.aptitude-test-fees.edit', $aptitudeTestFee) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Fee
                                </a>
                                <form action="{{ route('bursar.aptitude-test-fees.toggle-status', $aptitudeTestFee) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn {{ $aptitudeTestFee->is_active ? 'btn-secondary' : 'btn-success' }}">
                                        <i class="fas {{ $aptitudeTestFee->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                        {{ $aptitudeTestFee->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                @if($totalPayments == 0)
                                    <form action="{{ route('bursar.aptitude-test-fees.destroy', $aptitudeTestFee) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this aptitude test fee? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 4px solid #007bff !important;
}

.border-left-success {
    border-left: 4px solid #28a745 !important;
}

.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}
</style>
@endpush