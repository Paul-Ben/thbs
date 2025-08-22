@extends('bursar.layout')

@section('content')
<div class="container-fluid">
    <!-- <div class="page-inner"> -->
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
                    <li class="nav-item"><a href="#">Fee Management</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Application Fees</a></li>
                </ul>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">All Application Fees</h4>
                            <a href="{{ route('bursar.application-fees.create') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Add Application Fee
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Programme</th>
                                        <th>Department</th>
                                        <th>Level</th>
                                        <th>Amount (₦)</th>
                                        <th>Created At</th>
                                        <th style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($applicationFees as $fee)
                                        <tr>
                                            <td>{{ $fee->id }}</td>
                                            <td>{{ $fee->programme->name }}</td>
                                            <td>{{ $fee->programme->department->name }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $fee->programme->level }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">₦{{ number_format($fee->amount, 2) }}</span>
                                            </td>
                                            <td>{{ $fee->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('bursar.application-fees.show', $fee) }}" class="btn btn-link btn-info btn-lg" data-bs-toggle="tooltip" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('bursar.application-fees.edit', $fee) }}" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit Fee">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('bursar.application-fees.destroy', $fee) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this application fee? This action cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link btn-danger btn-lg" data-bs-toggle="tooltip" title="Delete Fee">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="py-4">
                                                    <i class="fa fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No application fees found</h5>
                                                    <p class="text-muted">Start by adding application fees for different programmes.</p>
                                                    <a href="{{ route('bursar.application-fees.create') }}" class="btn btn-primary">
                                                        <i class="fa fa-plus"></i> Add First Application Fee
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($applicationFees->hasPages())
                            <div class="d-flex justify-content-center">
                                {{ $applicationFees->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </div>
</div>
@endsection