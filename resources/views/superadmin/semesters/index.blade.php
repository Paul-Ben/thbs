@extends('layouts.dashboard')

@section('title', 'Semester Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Semester Management</h3>
                    <a href="{{ route('superadmin.semesters.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Semester
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Semester Name</th>
                                    <th>School Session</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($semesters as $semester)
                                    <tr>
                                        <td>{{ $semester->id }}</td>
                                        <td>{{ $semester->semester_name }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $semester->session->session_name }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($semester->is_current)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Current
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-circle"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $semester->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('superadmin.semesters.show', $semester) }}" 
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('superadmin.semesters.edit', $semester) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(!$semester->is_current)
                                                    <form action="{{ route('superadmin.semesters.set-current', $semester) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                title="Set as Current"
                                                                onclick="return confirm('Are you sure you want to set this semester as current?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('superadmin.semesters.destroy', $semester) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this semester? This action cannot be undone.')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No semesters found</h5>
                                                <p class="text-muted">Start by creating your first semester.</p>
                                                <a href="{{ route('superadmin.semesters.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Add New Semester
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($semesters->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $semesters->links() }}
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
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush