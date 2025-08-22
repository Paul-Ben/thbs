@extends('bursar.layout')

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
                    <li class="nav-item"><a href="#">School Fees</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">School Fee Management</h4>
                        <a href="{{ route('bursar.school-fees.create') }}" class="btn btn-primary btn-round ms-auto">
                            <i class="fa fa-plus"></i>
                            Add School Fee
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('bursar.school-fees.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Search by fee type...">
                            </div>
                            <div class="col-md-2">
                                <label for="programme_id" class="form-label">Programme</label>
                                <select class="form-select" id="programme_id" name="programme_id">
                                    <option value="">All Programmes</option>
                                    @foreach($programmes as $programme)
                                        <option value="{{ $programme->id }}" 
                                                {{ request('programme_id') == $programme->id ? 'selected' : '' }}>
                                            {{ $programme->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="session_id" class="form-label">Session</label>
                                <select class="form-select" id="session_id" name="session_id">
                                    <option value="">All Sessions</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->id }}" 
                                                {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                            {{ $session->session_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="semester_id" class="form-label">Semester</label>
                                <select class="form-select" id="semester_id" name="semester_id">
                                    <option value="">All Semesters</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}" 
                                                {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->semester_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="level_id" class="form-label">Level</label>
                                <select class="form-select" id="level_id" name="level_id">
                                    <option value="">All Levels</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" 
                                                {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-2">
                                <select class="form-select" name="fee_type">
                                    <option value="">All Fee Types</option>
                                    <option value="tuition" {{ request('fee_type') == 'tuition' ? 'selected' : '' }}>Tuition</option>
                                    <option value="accommodation" {{ request('fee_type') == 'accommodation' ? 'selected' : '' }}>Accommodation</option>
                                    <option value="library" {{ request('fee_type') == 'library' ? 'selected' : '' }}>Library</option>
                                    <option value="laboratory" {{ request('fee_type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                    <option value="sports" {{ request('fee_type') == 'sports' ? 'selected' : '' }}>Sports</option>
                                    <option value="medical" {{ request('fee_type') == 'medical' ? 'selected' : '' }}>Medical</option>
                                    <option value="development" {{ request('fee_type') == 'development' ? 'selected' : '' }}>Development</option>
                                    <option value="examination" {{ request('fee_type') == 'examination' ? 'selected' : '' }}>Examination</option>
                                    <option value="registration" {{ request('fee_type') == 'registration' ? 'selected' : '' }}>Registration</option>
                                    <option value="technology" {{ request('fee_type') == 'technology' ? 'selected' : '' }}>Technology</option>
                                    <option value="miscellaneous" {{ request('fee_type') == 'miscellaneous' ? 'selected' : '' }}>Miscellaneous</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('bursar.school-fees.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Programme</th>
                                    <th>Session</th>
                                    <th>Semester</th>
                                    <th>Level</th>
                                    <th>Fee Type</th>
                                    <th>Amount (₦)</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th style="width: 15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schoolFees as $fee)
                                    <tr>
                                        <td>{{ $fee->id }}</td>
                                        <td>{{ $fee->programme->name }}</td>
                                        <td>{{ $fee->schoolSession->session_name }}</td>
                                        <td>{{ $fee->semester->semester_name }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $fee->level->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ ucfirst($fee->fee_type) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">{{ $fee->currency }} {{ number_format($fee->amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $fee->is_active ? 'badge-success' : 'badge-warning' }}">
                                                {{ $fee->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $fee->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="{{ route('bursar.school-fees.show', $fee) }}" class="btn btn-link btn-info btn-lg" data-bs-toggle="tooltip" title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('bursar.school-fees.edit', $fee) }}" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit Fee">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('bursar.school-fees.destroy', $fee) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this school fee? This action cannot be undone.')">
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
                                        <td colspan="10" class="text-center">
                                            <div class="py-4">
                                                <i class="fa fa-graduation-cap fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No school fees found</h5>
                                                <p class="text-muted">Start by adding school fees for different programmes and levels.</p>
                                                <a href="{{ route('bursar.school-fees.create') }}" class="btn btn-primary">
                                                    <i class="fa fa-plus"></i> Add First School Fee
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($schoolFees->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $schoolFees->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection