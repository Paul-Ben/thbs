@extends('layouts.dashboard')

@section('title', 'Level Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Level Management</h3>
                    <a href="{{ route('superadmin.levels.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Level
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
                                    <th>Level Name</th>
                                    <th>Semester</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($levels as $level)
                                    <tr>
                                        <td>{{ $level->id }}</td>
                                        <td>{{ $level->name }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $level->semester->semester_name }}
                                            </span>
                                        </td>
                                        <td>{{ $level->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('superadmin.levels.show', $level) }}" 
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('superadmin.levels.edit', $level) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('superadmin.levels.destroy', $level) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this level? This action cannot be undone.')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No levels found</h5>
                                                <p class="text-muted">Start by creating your first level.</p>
                                                <a href="{{ route('superadmin.levels.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Add New Level
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($levels->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $levels->links() }}
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