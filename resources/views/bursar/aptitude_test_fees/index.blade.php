@extends('bursar.layout')

@section('title', 'Aptitude Test Fees Management')

@section('content')
<div class="container-fluid">
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
                    <li class="nav-item"><a href="#">Aptitude Test Fees</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Aptitude Test Fees</h3>
                    <a href="{{ route('bursar.aptitude-test-fees.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Fee
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('bursar.aptitude-test-fees.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="search">Search</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="Search by name, amount, or description..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-info mr-2">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('bursar.aptitude-test-fees.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Aptitude Test Fees Table -->
                    @if($aptitudeTestFees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Currency</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($aptitudeTestFees as $fee)
                                        <tr>
                                            <td>{{ $loop->iteration + ($aptitudeTestFees->currentPage() - 1) * $aptitudeTestFees->perPage() }}</td>
                                            <td>
                                                <strong>{{ $fee->name }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($fee->amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ strtoupper($fee->currency) }}</span>
                                            </td>
                                            <td>
                                                {{ $fee->description ? Str::limit($fee->description, 50) : 'No description' }}
                                            </td>
                                            <td>
                                                @if($fee->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $fee->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('bursar.aptitude-test-fees.show', $fee) }}" 
                                                       class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('bursar.aptitude-test-fees.edit', $fee) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('bursar.aptitude-test-fees.toggle-status', $fee) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $fee->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                                                title="{{ $fee->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i class="fas {{ $fee->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('bursar.aptitude-test-fees.destroy', $fee) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to delete this aptitude test fee?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted">
                                    Showing {{ $aptitudeTestFees->firstItem() }} to {{ $aptitudeTestFees->lastItem() }} 
                                    of {{ $aptitudeTestFees->total() }} results
                                </p>
                            </div>
                            <div>
                                {{ $aptitudeTestFees->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No aptitude test fees found</h5>
                            <p class="text-muted">Start by creating your first aptitude test fee.</p>
                            <a href="{{ route('bursar.aptitude-test-fees.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Fee
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form on filter change
    document.getElementById('status').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush